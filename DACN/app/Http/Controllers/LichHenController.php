<?php

namespace App\Http\Controllers;

use App\Models\LichHen;
use App\Models\BacSi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\DichVu;
use App\Models\LichLamViec;
use Illuminate\Support\Facades\DB;
use App\Services\LichKhamService;
use App\Models\HoaDon;
use App\Services\RoomAvailabilityService;

class LichHenController extends Controller
{
    protected $roomService;

    public function __construct(RoomAvailabilityService $roomService)
    {
        $this->roomService = $roomService;
    }
    public function create(BacSi $bacSi)
    {
        $danhSachDichVu = DichVu::all();
        return view('public.lichhen.create', compact('bacSi', 'danhSachDichVu'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'bac_si_id' => 'required|exists:bac_sis,id',
            'dich_vu_id' => 'required|exists:dich_vus,id',
            'ngay_hen' => 'required|date_format:Y-m-d',
            'thoi_gian_hen' => 'required|date_format:H:i',
            'ghi_chu' => 'nullable|string|max:1000',
        ]);

        $dichVu = DichVu::find($validatedData['dich_vu_id']);
        $duration = $dichVu ? (int) ($dichVu->thoi_gian_uoc_tinh ?? 30) : 30;

        $bacSiId = $validatedData['bac_si_id'];
        $date = $validatedData['ngay_hen'];
        $slotStart = Carbon::parse($date . ' ' . $validatedData['thoi_gian_hen']);
        $slotEnd = $slotStart->copy()->addMinutes($duration);

        // tính weekday (Carbon::dayOfWeek : 0=Sun..6=Sat)
        try {
            $weekday = Carbon::parse($date)->dayOfWeek;
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['ngay_hen' => 'Ngày không hợp lệ'])->withInput();
        }

        $lichLamViec = LichLamViec::where('bac_si_id', $bacSiId)
            ->where('ngay_trong_tuan', $weekday)
            ->first();

        if (! $lichLamViec) {
            return redirect()->back()->withErrors(['thoi_gian_hen' => 'Bác sĩ không làm việc vào ngày này'])->withInput();
        }

        $shiftStart = Carbon::parse($date . ' ' . ($lichLamViec->thoi_gian_bat_dau ?? '08:00:00'));
        $shiftEnd   = Carbon::parse($date . ' ' . ($lichLamViec->thoi_gian_ket_thuc ?? '17:00:00'));

        if ($slotStart->lt($shiftStart) || $slotEnd->gt($shiftEnd)) {
            return redirect()->back()->withErrors(['thoi_gian_hen' => "Khung giờ không hợp lệ: lịch hẹn kéo dài {$duration} phút và không nằm trong ca làm việc của bác sĩ"])->withInput();
        }

        // Re-check overlaps theo duration hiện tại
        $conflict = LichHen::where('bac_si_id', $bacSiId)
            ->where('ngay_hen', $date)
            ->get()
            ->filter(function ($item) use ($slotStart, $duration, $date) {
                $existStart = Carbon::parse($date . ' ' . $item->thoi_gian_hen);
                $existDur = $item->dichVu ? ($item->dichVu->thoi_gian_uoc_tinh ?? 30) : 30;
                $existEnd = $existStart->copy()->addMinutes($existDur);
                $reqStart = $slotStart->copy();
                $reqEnd = $reqStart->copy()->addMinutes($duration);
                return $reqStart->lt($existEnd) && $reqEnd->gt($existStart);
            })->isNotEmpty();

        if ($conflict) {
            return redirect()->back()->withErrors(['thoi_gian_hen' => 'Khung giờ đã bị đặt bởi người khác, vui lòng chọn khung giờ khác'])->withInput();
        }

        // Bổ sung lớp service để kiểm tra xung đột nâng cao (lịch nghỉ, ca điều chỉnh, và chính bệnh nhân)
        $svc = app(LichKhamService::class);
        $doctorCheck = $svc->hasConflictForDoctor($bacSiId, $date, $validatedData['thoi_gian_hen'], $duration, null);
        if ($doctorCheck['conflict']) {
            return redirect()->back()->withErrors(['thoi_gian_hen' => $doctorCheck['details'][0] ?? 'Khung giờ không khả dụng'])->withInput();
        }
        $patientCheck = $svc->hasPatientConflict(Auth::id(), $date, $validatedData['thoi_gian_hen'], $duration, null);
        if ($patientCheck['conflict']) {
            return redirect()->back()->withErrors(['thoi_gian_hen' => $patCheck['details'][0] ?? 'Bạn đã có lịch trong khung giờ này'])->withInput();
        }

        // ✅ KIỂM TRA XUNG ĐỘT PHÒNG
        $lichLamViec = LichLamViec::where('bac_si_id', $bacSiId)
            ->where('ngay_trong_tuan', $weekday)
            ->first();

        if ($lichLamViec && $lichLamViec->phong_id) {
            $timeStart = Carbon::parse($date . ' ' . $validatedData['thoi_gian_hen']);
            $timeEnd = $timeStart->copy()->addMinutes($duration);

            $roomConflict = $this->roomService->checkRoomConflict(
                $lichLamViec->phong_id,
                $timeStart,
                $timeEnd
            );

            if ($roomConflict['conflict']) {
                $message = 'Phòng "' . ($lichLamViec->phong->ten ?? '') . '" đã bị đặt trong khung giờ này';
                if (!empty($roomConflict['details'])) {
                    $message .= ': ' . $roomConflict['details'][0];
                }
                return redirect()->back()->withErrors(['thoi_gian_hen' => $message])->withInput();
            }
        }        $lichHen = null; // THÊM: Declare variable
        DB::transaction(function () use ($validatedData, &$lichHen) {
            // THÊM: Lấy giá dịch vụ để lưu vào tong_tien
            $dichVu = DichVu::find($validatedData['dich_vu_id']);
            $tongTien = $dichVu ? $dichVu->gia : 0;

            $lichHen = LichHen::create([
                'user_id' => auth()->id(),
                'bac_si_id' => $validatedData['bac_si_id'],
                'dich_vu_id' => $validatedData['dich_vu_id'],
                'tong_tien' => $tongTien, // THÊM: Lưu giá tại thời điểm đặt
                'ngay_hen' => $validatedData['ngay_hen'],
                'thoi_gian_hen' => $validatedData['thoi_gian_hen'],
                'ghi_chu' => $validatedData['ghi_chu'] ?? null,
                // Đổi 'pending' thành 'Chờ xác nhận' để thống nhất với migration
                'trang_thai' => 'Chờ xác nhận',
            ]);

            // THÊM: Tự động tạo hóa đơn luôn
            HoaDon::create([
                'lich_hen_id' => $lichHen->id,
                'user_id' => auth()->id(),
                'tong_tien' => $tongTien,
                'trang_thai' => 'Chưa thanh toán',
            ]);
        });

        // THÊM: Redirect sang trang thanh toán thay vì success
        if ($lichHen) {
            return redirect()->route('patient.payment', $lichHen->id)->with('success', 'Đặt lịch thành công! Vui lòng thanh toán để hoàn tất.');
        }

        return redirect()->route('lichhen.thanhcong')->with('success', 'Đặt lịch thành công');
    }

    // Bệnh nhân xem lịch hẹn của mình
    public function myAppointments()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập');
        }

        $danhSachLichHen = LichHen::where('user_id', Auth::id()) // ĐỔI từ benh_nhan_id thành user_id
            ->with(['bacSi', 'dichVu'])
            ->orderBy('ngay_hen', 'desc')
            ->orderBy('thoi_gian_hen', 'desc')
            ->get();

        return view('public.lichhen.my_appointments', compact('danhSachLichHen'));
    }

    // Form sửa lịch hẹn
    public function edit(LichHen $lichHen)
    {
        abort_unless($lichHen->user_id === Auth::id(), 403);
        abort_unless(in_array($lichHen->trang_thai, ['pending', 'confirmed']), 403);

        return view('public.lichhen.edit', compact('lichHen'));
    }

    // Cập nhật (đổi ngày/giờ)
    public function update(Request $request, $id)
    {
        $lichHen = LichHen::findOrFail($id);

        // Kiểm tra quyền sở hữu - ĐỔI từ benh_nhan_id thành user_id
        if ($lichHen->user_id != Auth::id()) {
            return back()->with('error', 'Bạn không có quyền sửa lịch hẹn này');
        }

        // Chỉ cho phép sửa nếu trạng thái phù hợp
        if (!in_array($lichHen->trang_thai, ['Chờ xác nhận', 'Đã xác nhận'])) {
            return back()->with('error', 'Không thể sửa lịch hẹn này');
        }

        $request->validate([
            'ngay_hen' => 'required|date|after_or_equal:today',
            'thoi_gian_hen' => 'required',
            'ghi_chu' => 'nullable|string'
        ]);

        // Kiểm tra xung đột nâng cao trước khi cập nhật
        $duration = $lichHen->dichVu ? (int) ($lichHen->dichVu->thoi_gian_uoc_tinh ?? 30) : 30;
        $svc = app(LichKhamService::class);
        $docCheck = $svc->hasConflictForDoctor($lichHen->bac_si_id, $request->ngay_hen, $request->thoi_gian_hen, $duration, $lichHen->id);
        if ($docCheck['conflict']) {
            return back()->withErrors(['thoi_gian_hen' => $docCheck['details'][0] ?? 'Khung giờ không khả dụng'])->withInput();
        }
        $patCheck = $svc->hasPatientConflict(Auth::id(), $request->ngay_hen, $request->thoi_gian_hen, $duration, $lichHen->id);
        if ($patCheck['conflict']) {
            return back()->withErrors(['thoi_gian_hen' => $patCheck['details'][0] ?? 'Bạn đã có lịch trong khung giờ này'])->withInput();
        }

        // ✅ KIỂM TRA XUNG ĐỘT PHÒNG
        $weekday = Carbon::parse($request->ngay_hen)->dayOfWeek;
        $lichLamViec = LichLamViec::where('bac_si_id', $lichHen->bac_si_id)
            ->where('ngay_trong_tuan', $weekday)
            ->first();

        if ($lichLamViec && $lichLamViec->phong_id) {
            $timeStart = Carbon::parse($request->ngay_hen . ' ' . $request->thoi_gian_hen);
            $timeEnd = $timeStart->copy()->addMinutes($duration);

            $roomConflict = $this->roomService->checkRoomConflict(
                $lichLamViec->phong_id,
                $timeStart,
                $timeEnd,
                $lichHen->id
            );

            if ($roomConflict['conflict']) {
                $message = 'Phòng "' . ($lichLamViec->phong->ten ?? '') . '" đã bị đặt trong khung giờ này';
                if (!empty($roomConflict['details'])) {
                    $message .= ': ' . $roomConflict['details'][0];
                }
                return back()->withErrors(['thoi_gian_hen' => $message])->withInput();
            }
        }        $lichHen->update([
            'ngay_hen' => $request->ngay_hen,
            'thoi_gian_hen' => $request->thoi_gian_hen,
            'ghi_chu' => $request->ghi_chu,
            'trang_thai' => 'Chờ xác nhận' // Reset về chờ xác nhận
        ]);

        return back()->with('success', 'Cập nhật lịch hẹn thành công');
    }

    // Hủy lịch hẹn
    public function cancel($id)
    {
        $lichHen = LichHen::findOrFail($id);

        // Kiểm tra quyền sở hữu - ĐỔI từ benh_nhan_id thành user_id
        if ($lichHen->user_id != Auth::id()) {
            return back()->with('error', 'Bạn không có quyền hủy lịch hẹn này');
        }

        // Chỉ cho phép hủy nếu chưa hoàn thành
        if (in_array($lichHen->trang_thai, ['Đã hủy', 'Hoàn thành'])) {
            return back()->with('error', 'Không thể hủy lịch hẹn này');
        }

        $lichHen->update(['trang_thai' => 'Đã hủy']);

        return back()->with('success', 'Đã hủy lịch hẹn');
    }

    // API: danh sách chuyên khoa (rút từ cột text của bác sĩ, giữ nguyên lộ trình mục 10 là "Chưa")
    public function ajaxChuyenKhoa(Request $request)
    {
        $list = BacSi::query()
            ->whereNotNull('chuyen_khoa')
            ->where('chuyen_khoa', '<>', '')
            ->distinct()
            ->orderBy('chuyen_khoa')
            ->pluck('chuyen_khoa');

        return response()->json($list);
    }

    // API: danh sách bác sĩ theo chuyên khoa (text)
    public function ajaxBacSiByChuyenKhoa(Request $request)
    {
        $ck = $request->query('ck');
        $doctors = BacSi::query()
            ->when($ck, fn($q) => $q->where('chuyen_khoa', $ck))
            ->orderBy('ho_ten')
            ->get(['id', 'ho_ten', 'chuyen_khoa']);

        return response()->json($doctors);
    }

    // Admin xem tất cả lịch hẹn
    public function adminIndex(Request $request)
    {
        $query = LichHen::with(['user', 'bacSi.lichLamViecs.phong', 'dichVu', 'hoaDon']);

        // Lọc theo Bác sĩ
        if ($request->filled('bac_si_id')) {
            $query->where('bac_si_id', $request->bac_si_id);
        }

        // Lọc theo Phòng (thông qua lịch làm việc của bác sĩ)
        if ($request->filled('phong_id')) {
            $phongId = $request->phong_id;
            $query->whereHas('bacSi.lichLamViecs', function($q) use ($phongId) {
                $q->where('phong_id', $phongId);
            });
        }

        // Lọc theo Trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        $danhSachLichHen = $query->orderBy('ngay_hen', 'desc')
            ->orderBy('thoi_gian_hen', 'desc')
            ->paginate(20)
            ->appends($request->query());

        return view('admin.lichhen.index', compact('danhSachLichHen'));
    }

    // Admin cập nhật trạng thái lịch hẹn
    public function updateStatus(Request $request, LichHen $lichHen)
    {
        $request->validate([
            'trang_thai' => 'required|in:Chờ xác nhận,Đã xác nhận,Đã hủy,Hoàn thành'
        ]);

        $lichHen->update([
            'trang_thai' => $request->trang_thai
        ]);

        return back()->with('success', 'Cập nhật trạng thái thành công');
    }

    /**
     * ✅ THÊM METHOD MỚI: AJAX - Lấy danh sách bác sĩ theo chuyên khoa
     */
    public function getBacSiByChuyenKhoa($chuyenKhoa)
    {
        $bacSis = BacSi::where('chuyen_khoa', $chuyenKhoa)
            ->where('trang_thai', 'Đang hoạt động')
            ->select('id', 'ho_ten', 'kinh_nghiem')
            ->get();

        return response()->json($bacSis);
    }

    /**
     * ✅ THÊM METHOD MỚI: AJAX - Lấy lịch làm việc của bác sĩ
     */
    public function getLichLamViec(BacSi $bacSi)
    {
        $lichLamViecs = $bacSi->lichLamViecs()
            ->orderBy('ngay_trong_tuan')
            ->get()
            ->map(function($lich) {
                return [
                    'ngay_trong_tuan' => $lich->ngay_trong_tuan,
                    'thoi_gian_bat_dau' => $lich->thoi_gian_bat_dau,
                    'thoi_gian_ket_thuc' => $lich->thoi_gian_ket_thuc,
                ];
            });

        return response()->json($lichLamViecs);
    }

    /**
     * ✅ THÊM METHOD MỚI: API - Lấy khung giờ trống của bác sĩ
     */
    public function getAvailableTimeSlots(BacSi $bacSi, $ngay)
    {
        try {
            $date = Carbon::parse($ngay);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ngày không hợp lệ'], 400);
        }

        // Lấy dich_vu_id từ query string
        $dichVuId = request()->query('dich_vu_id');
        if (!$dichVuId) {
            return response()->json(['error' => 'Thiếu dịch vụ'], 400);
        }

        $dichVu = DichVu::find($dichVuId);
        if (!$dichVu) {
            return response()->json(['error' => 'Dịch vụ không tồn tại'], 404);
        }

        $duration = (int) ($dichVu->thoi_gian_uoc_tinh ?? 30);

        // Kiểm tra bác sĩ có làm việc vào ngày này không
        $weekday = $date->dayOfWeek; // 0=CN, 1=T2, ..., 6=T7
        $lichLamViec = LichLamViec::where('bac_si_id', $bacSi->id)
            ->where('ngay_trong_tuan', $weekday)
            ->first();

        if (!$lichLamViec) {
            return response()->json([
                'message' => 'Bác sĩ không làm việc vào ngày này',
                'slots' => []
            ]);
        }

        // Lấy giờ làm việc
        $shiftStart = Carbon::parse($date->format('Y-m-d') . ' ' . $lichLamViec->thoi_gian_bat_dau);
        $shiftEnd = Carbon::parse($date->format('Y-m-d') . ' ' . $lichLamViec->thoi_gian_ket_thuc);

        // Lấy danh sách lịch hẹn đã đặt trong ngày
        $bookedAppointments = LichHen::where('bac_si_id', $bacSi->id)
            ->where('ngay_hen', $date->format('Y-m-d'))
            ->whereIn('trang_thai', ['Chờ xác nhận', 'Đã xác nhận'])
            ->get();

        // Tạo danh sách khung giờ trống (mỗi 30 phút)
        $slots = [];
        $current = $shiftStart->copy();
        $now = Carbon::now();

        while ($current->copy()->addMinutes($duration)->lte($shiftEnd)) {
            $slotEnd = $current->copy()->addMinutes($duration);

            // Bỏ qua nếu khung giờ đã qua (nếu là hôm nay)
            if ($date->isToday() && $current->lte($now)) {
                $current->addMinutes(30);
                continue;
            }

            // Kiểm tra xem khung giờ này có bị trùng với lịch hẹn nào không
            $isAvailable = true;
            foreach ($bookedAppointments as $appointment) {
                $appointmentStart = Carbon::parse($date->format('Y-m-d') . ' ' . $appointment->thoi_gian_hen);
                $appointmentDuration = $appointment->dichVu ? ($appointment->dichVu->thoi_gian_uoc_tinh ?? 30) : 30;
                $appointmentEnd = $appointmentStart->copy()->addMinutes($appointmentDuration);

                // Kiểm tra overlap
                if ($current->lt($appointmentEnd) && $slotEnd->gt($appointmentStart)) {
                    $isAvailable = false;
                    break;
                }
            }

            if ($isAvailable) {
                $slots[] = [
                    'time' => $current->format('H:i'),
                    'label' => $current->format('H:i') . ' - ' . $slotEnd->format('H:i')
                ];
            }

            $current->addMinutes(30); // Mỗi slot cách nhau 30 phút
        }

        return response()->json([
            'message' => 'Thành công',
            'slots' => $slots,
            'shift' => [
                'start' => $lichLamViec->thoi_gian_bat_dau,
                'end' => $lichLamViec->thoi_gian_ket_thuc
            ]
        ]);
    }

    // Trang lịch bệnh nhân: chọn bác sĩ, dịch vụ và ngày để xem slot trống
    public function publicCalendar()
    {
        $bacSis = BacSi::orderBy('ho_ten')->get(['id','ho_ten']);
        $dichVus = DichVu::orderBy('ten')->get(['id','ten','thoi_gian_uoc_tinh']);
        return view('public.lichhen.calendar', compact('bacSis','dichVus'));
    }
}

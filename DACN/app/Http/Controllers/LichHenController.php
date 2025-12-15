<?php

namespace App\Http\Controllers;

use App\Models\LichHen;
use App\Models\BacSi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\DichVu;
use Illuminate\Support\Facades\Schema;
use App\Models\LichLamViec;
use App\Models\CaDieuChinhBacSi;
use App\Models\LichNghi;
use Illuminate\Support\Facades\DB;
use App\Services\LichKhamService;
use App\Models\HoaDon;
use App\Models\Coupon;
use App\Services\RoomAvailabilityService;
use App\Services\MedicalWorkflowService;

class LichHenController extends Controller
{
    protected $roomService;
    protected $workflowService;

    public function __construct(
        RoomAvailabilityService $roomService,
        MedicalWorkflowService $workflowService
    ) {
        $this->roomService = $roomService;
        $this->workflowService = $workflowService;
    }
    public function create(BacSi $bacSi)
    {
        // Lấy danh sách chuyên khoa của bác sĩ (nếu có quan hệ many-to-many)
        $bacSi->load('chuyenKhoas');

        $ckIds = $bacSi->chuyenKhoas->pluck('id')->toArray();

        $warnNoMapping = false;

        if (!empty($ckIds)) {
            // Lấy dịch vụ đã được map trong pivot table
            $danhSachDichVu = DichVu::with('chuyenKhoas')
                ->whereHas('chuyenKhoas', function($q) use ($ckIds) {
                    $q->whereIn('chuyen_khoas.id', $ckIds);
                })->orderBy('ten_dich_vu')->get();

            if ($danhSachDichVu->isEmpty()) {
                // Có chuyên khoa nhưng chưa map dịch vụ -> cảnh báo và fallback sang tất cả dịch vụ
                $warnNoMapping = true;
                $danhSachDichVu = DichVu::with('chuyenKhoas')->orderBy('ten_dich_vu')->get();
            }
        } else {
            // Bác sĩ không có chuyên khoa được cấu hình -> cảnh báo và gửi tất cả dịch vụ
            $warnNoMapping = true;
            $danhSachDichVu = DichVu::with('chuyenKhoas')->orderBy('ten_dich_vu')->get();
        }

        return view('public.lichhen.create', compact('bacSi', 'danhSachDichVu', 'warnNoMapping'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'bac_si_id' => 'required|exists:bac_sis,id',
            'dich_vu_id' => 'required|exists:dich_vus,id',
            'ngay_hen' => 'required|date_format:Y-m-d',
            'thoi_gian_hen' => 'required|date_format:H:i',
            'coupon_code' => 'nullable|string|max:50',
            'ten_benh_nhan' => 'nullable|string|max:255',
            'sdt_benh_nhan' => 'nullable|string|max:50',
            'email_benh_nhan' => 'nullable|email|max:255',
            'ngay_sinh_benh_nhan' => 'nullable|date',
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

        // Lấy các ca làm việc định kỳ và ca add (CaDieuChinh kiểu 'add') cho ngày này
        $lichLamViecs = LichLamViec::where('bac_si_id', $bacSiId)
            ->where('ngay_trong_tuan', $weekday)
            ->orderBy('thoi_gian_bat_dau')
            ->get();

        $overridesAdd = CaDieuChinhBacSi::where('bac_si_id', $bacSiId)
            ->whereDate('ngay', Carbon::parse($date)->format('Y-m-d'))
            ->where('hanh_dong', 'add')
            ->get();

        if ($lichLamViecs->isEmpty() && $overridesAdd->isEmpty()) {
            return redirect()->back()->withErrors(['thoi_gian_hen' => 'Bác sĩ không làm việc vào ngày này'])->withInput();
        }

        // Tổ hợp shifts (schedule + overrides)
        $shifts = [];
        foreach ($lichLamViecs as $lv) {
            $shifts[] = [
                'start' => Carbon::parse($date . ' ' . $lv->thoi_gian_bat_dau),
                'end' => Carbon::parse($date . ' ' . $lv->thoi_gian_ket_thuc),
                'phong_id' => $lv->phong_id ?? null,
                'source' => 'schedule',
            ];
        }
        foreach ($overridesAdd as $ov) {
            $shifts[] = [
                'start' => Carbon::parse($date . ' ' . $ov->gio_bat_dau),
                'end' => Carbon::parse($date . ' ' . $ov->gio_ket_thuc),
                'phong_id' => $ov->phong_id ?? null,
                'source' => 'override',
            ];
        }
        usort($shifts, function ($a, $b) { return $a['start']->lt($b['start']) ? -1 : 1; });

        // Kiểm tra slot nằm trong 1 ca hợp lệ không
        $slotInShift = false;
        $slotShiftPhongId = null;
        foreach ($shifts as $s) {
            if ($slotStart->gte($s['start']) && $slotEnd->lte($s['end'])) {
                $slotInShift = true;
                $slotShiftPhongId = $s['phong_id'] ?? null;
                break;
            }
        }

        if (! $slotInShift) {
            return redirect()->back()->withErrors(['thoi_gian_hen' => "Khung giờ không hợp lệ: lịch hẹn kéo dài {$duration} phút và không nằm trong ca làm việc của bác sĩ"])->withInput();
        }

        // Kiểm tra mở rộng: Lịch nghỉ (LichNghi)
        $lichNghiExists = LichNghi::where('bac_si_id', $bacSiId)
            ->whereDate('ngay', $date)
            ->where(function($q) use ($slotStart, $slotEnd) {
                $q->where('bat_dau', '<', $slotEnd->format('H:i:s'))
                  ->where('ket_thuc', '>', $slotStart->format('H:i:s'));
            })->exists();
        if ($lichNghiExists) {
            return redirect()->back()->withErrors(['thoi_gian_hen' => 'Bác sĩ đang nghỉ / không làm việc vào khung giờ này'])->withInput();
        }

        // Check CaDieuChinh with 'remove' action for explicit removal
        $overlapRemove = CaDieuChinhBacSi::where('bac_si_id', $bacSiId)
            ->whereDate('ngay', $date)
            ->where('hanh_dong', 'remove')
            ->where(function($q) use ($slotStart, $slotEnd) {
                $q->where('gio_bat_dau', '<', $slotEnd->format('H:i:s'))
                  ->where('gio_ket_thuc', '>', $slotStart->format('H:i:s'));
            })->exists();
        if ($overlapRemove) {
            return redirect()->back()->withErrors(['thoi_gian_hen' => 'Khung giờ đã bị bác sĩ hủy vào ngày này'])->withInput();
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

        // ✅ KIỂM TRA XUNG ĐỘT BÁC SĨ (lịch nghỉ, ca điều chỉnh, lịch hẹn khác)
        $svc = app(LichKhamService::class);
        $doctorCheck = $svc->hasConflictForDoctor($bacSiId, $date, $validatedData['thoi_gian_hen'], $duration, null);
        if ($doctorCheck['conflict']) {
            return redirect()->back()->withErrors(['thoi_gian_hen' => $doctorCheck['details'][0] ?? 'Khung giờ không khả dụng'])->withInput();
        }

        // ✅ KIỂM TRA XUNG ĐỘT BỆNH NHÂN (không cho đặt 2 lịch trùng giờ)
        $patientCheck = $svc->hasPatientConflict(Auth::id(), $date, $validatedData['thoi_gian_hen'], $duration, null);
        if ($patientCheck['conflict']) {
            return redirect()->back()->withErrors(['thoi_gian_hen' => $patientCheck['details'][0] ?? 'Bạn đã có lịch trong khung giờ này'])->withInput();
        }

        // ✅ KIỂM TRA XUNG ĐỘT PHÒNG
        $timeStart = Carbon::parse($date . ' ' . $validatedData['thoi_gian_hen']);
        $timeEnd = $timeStart->copy()->addMinutes($duration);

        if (!empty($slotShiftPhongId)) {
            $roomConflict = $this->roomService->checkRoomConflict(
                $slotShiftPhongId,
                $timeStart,
                $timeEnd,
                null, // ignoreLichHenId
                null, // ignoreLichLamViecId
                $bacSiId
            );

            if ($roomConflict['conflict']) {
                $message = 'Phòng đã bị đặt trong khung giờ này';
                if (!empty($roomConflict['details'])) {
                    $message .= ': ' . $roomConflict['details'][0];
                }
                return redirect()->back()->withErrors(['thoi_gian_hen' => $message])->withInput();
            }
        }

        // --- VALIDATE: đảm bảo dịch vụ hợp lệ với chuyên khoa của bác sĩ (dùng pivot table mapping) ---
        try {
            $bacSi = BacSi::with('chuyenKhoas')->findOrFail($bacSiId);
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors(['bac_si_id' => 'Bác sĩ không tồn tại'])->withInput();
        }

        $validMapping = false;
        if ($dichVu) {
            $dichVu->load('chuyenKhoas');
            $dIds = $dichVu->chuyenKhoas->pluck('id')->toArray();
            $bIds = $bacSi->chuyenKhoas->pluck('id')->toArray();

            // Nếu dich_vu không gắn chuyên khoa (danh sách rỗng) thì cho phép (fallback)
            if (empty($dIds)) {
                $validMapping = true;
            } else {
                if (count(array_intersect($dIds, $bIds)) > 0) {
                    $validMapping = true;
                }
            }
        }

        if (! $validMapping) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['dich_vu_id' => 'Dịch vụ đã chọn không khớp với chuyên khoa của bác sĩ. Vui lòng chọn dịch vụ phù hợp hoặc chọn bác sĩ khác.']);
        }


        // ✅ CHECK LOCK (slot reservation) BEFORE CREATING LỊCH HẸN via DB SlotLock
        $existingLock = \App\Models\SlotLock::where('bac_si_id', $bacSiId)
            ->where('ngay', $date)
            ->where('gio', $validatedData['thoi_gian_hen'])
            ->first();
        if ($existingLock && !$existingLock->isExpired() && $existingLock->user_id !== auth()->id()) {
            return redirect()->back()->withErrors(['thoi_gian_hen' => 'Khung giờ đang được giữ bởi người khác. Vui lòng chọn khung giờ khác'])->withInput();
        }

        // ✅ TẠO LỊCH HẸN TRONG TRANSACTION
        $lichHen = null;
        try {
                DB::transaction(function () use ($validatedData, &$lichHen, $bacSiId, $date) {
            // THÊM: Lấy giá dịch vụ để lưu vào tong_tien
            $dichVu = DichVu::find($validatedData['dich_vu_id']);
            $tongTien = $dichVu ? $dichVu->gia : 0;
            // Áp mã giảm giá nếu có (server-side authoritative)
            if (!empty($validatedData['coupon_code'])) {
                $coupon = Coupon::where('ma_giam_gia', $validatedData['coupon_code'])->where('kich_hoat', true)->first();
                if ($coupon) {
                    if ($coupon->kiemTraHopLe($tongTien)) {
                        $discount = $coupon->tinhGiamGia($tongTien);
                        $tongTien = max(0, $tongTien - $discount);
                    } else {
                        // Không hợp lệ
                        throw new \Exception('Mã giảm giá không hợp lệ');
                    }
                } else {
                    throw new \Exception('Mã giảm giá không tồn tại hoặc đã hết hạn');
                }
            }

            $lichHen = LichHen::create([
                'user_id' => auth()->id(),
                'bac_si_id' => $validatedData['bac_si_id'],
                'dich_vu_id' => $validatedData['dich_vu_id'],
                'tong_tien' => $tongTien, // THÊM: Lưu giá tại thời điểm đặt
                'ngay_hen' => $validatedData['ngay_hen'],
                'thoi_gian_hen' => $validatedData['thoi_gian_hen'],
                'ghi_chu' => $validatedData['ghi_chu'] ?? null,
                'ten_benh_nhan' => $validatedData['ten_benh_nhan'] ?? (auth()->check() ? auth()->user()->name : null),
                'sdt_benh_nhan' => $validatedData['sdt_benh_nhan'] ?? (auth()->check() ? auth()->user()->so_dien_thoai : null),
                'email_benh_nhan' => $validatedData['email_benh_nhan'] ?? (auth()->check() ? auth()->user()->email : null),
                'ngay_sinh_benh_nhan' => $validatedData['ngay_sinh_benh_nhan'] ?? (auth()->check() ? auth()->user()->ngay_sinh : null),
                'trang_thai' => \App\Models\LichHen::STATUS_PENDING_VN, // Trạng thái ban đầu
            ]);

            // THÊM: Tự động tạo hóa đơn luôn
            $hoaDonData = [
                'lich_hen_id' => $lichHen->id,
                'user_id' => auth()->id(),
                'tong_tien' => $tongTien,
                'trang_thai' => \App\Models\HoaDon::STATUS_UNPAID_VN,
            ];
            if (!empty($validatedData['coupon_code'])) {
                $coupon = Coupon::where('ma_giam_gia', $validatedData['coupon_code'])->where('kich_hoat', true)->first();
                if ($coupon && $coupon->kiemTraHopLe($tongTien)) {
                    $hoaDonData['coupon_id'] = $coupon->id;
                    $hoaDonData['giam_gia'] = $coupon->tinhGiamGia($dichVu->gia);
                }
            }
            if (!HoaDon::where('lich_hen_id', $lichHen->id)->exists()) {
                HoaDon::create($hoaDonData);
            }
            });
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['coupon_code' => $e->getMessage() ?: 'Không thể tạo lịch hẹn: lỗi hệ thống']);
        }

            // Release the slot lock if we held it
        try {
            \App\Models\SlotLock::where('bac_si_id', $bacSiId)
                ->where('ngay', $date)
                ->where('gio', $validatedData['thoi_gian_hen'])
                ->where('user_id', auth()->id())
                ->delete();
        } catch (\Throwable $e) {
            // non-critical, do not block booking if release fails
        }

        // THÊM: Redirect sang trang thanh toán thay vì success
        if ($lichHen) {
            return redirect()->route('patient.lichhen.payment', $lichHen->id)->with('success', 'Đặt lịch thành công! Vui lòng thanh toán để hoàn tất.');
        }

        return redirect()->route('lichhen.thanhcong')->with('success', 'Đặt lịch thành công');
    }

    // Bệnh nhân xem lịch hẹn của mình
    public function myAppointments()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập');
        }

        $danhSachLichHen = LichHen::where('user_id', Auth::id())
            ->with(['bacSi', 'dichVu', 'hoaDon'])
            ->orderBy('ngay_hen', 'desc')
            ->orderBy('thoi_gian_hen', 'desc')
            ->get();

        return view('patient.lichhen.index', compact('danhSachLichHen'));
    }

    // Form sửa lịch hẹn
    public function edit(LichHen $lichHen)
    {
        abort_unless($lichHen->user_id === Auth::id(), 403);
        abort_unless(in_array($lichHen->trang_thai, [\App\Models\LichHen::STATUS_PENDING_VN, \App\Models\LichHen::STATUS_CONFIRMED_VN]), 403);

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
        if (!in_array($lichHen->trang_thai, [\App\Models\LichHen::STATUS_PENDING_VN, \App\Models\LichHen::STATUS_CONFIRMED_VN])) {
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
            $dateOnly = $request->ngay_hen instanceof \Carbon\Carbon ? $request->ngay_hen->format('Y-m-d') : (string)$request->ngay_hen;
            $timeStart = Carbon::parse($dateOnly . ' ' . ($request->thoi_gian_hen ?? '00:00:00'));
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
            'trang_thai' => \App\Models\LichHen::STATUS_PENDING_VN // Reset về chờ xác nhận
        ]);

        return back()->with('success', 'Cập nhật lịch hẹn thành công');
    }

    // Xóa/Hủy lịch hẹn (RESTful destroy method)
    public function destroy(LichHen $lichHen)
    {
        // Kiểm tra quyền sở hữu
        if ($lichHen->user_id != Auth::id()) {
            return back()->with('error', 'Bạn không có quyền hủy lịch hẹn này');
        }

        // Chỉ cho phép hủy nếu chưa hoàn thành
        if (in_array($lichHen->trang_thai, [\App\Models\LichHen::STATUS_CANCELLED_VN, \App\Models\LichHen::STATUS_COMPLETED_VN])) {
            return back()->with('error', 'Không thể hủy lịch hẹn này');
        }

        $lichHen->update(['trang_thai' => \App\Models\LichHen::STATUS_CANCELLED_VN]);

        return redirect()->route('patient.lichhen.index')->with('success', 'Đã hủy lịch hẹn thành công');
    }

    // Hủy lịch hẹn (Legacy method - kept for backward compatibility)
    public function cancel($id)
    {
        $lichHen = LichHen::findOrFail($id);

        // Kiểm tra quyền sở hữu - ĐỔI từ benh_nhan_id thành user_id
        if ($lichHen->user_id != Auth::id()) {
            return back()->with('error', 'Bạn không có quyền hủy lịch hẹn này');
        }

        // Chỉ cho phép hủy nếu chưa hoàn thành
        if (in_array($lichHen->trang_thai, [\App\Models\LichHen::STATUS_CANCELLED_VN, \App\Models\LichHen::STATUS_COMPLETED_VN])) {
            return back()->with('error', 'Không thể hủy lịch hẹn này');
        }

        $lichHen->update(['trang_thai' => \App\Models\LichHen::STATUS_CANCELLED_VN]);

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

        // Lấy ca làm việc/ca add (nhiều ca, nếu có) — xử lý sau khi tính $shifts

        // Lấy giờ làm việc
                // Kiểm tra bác sĩ có làm việc vào ngày này không
                $weekday = $date->dayOfWeek; // 0=CN, 1=T2, ..., 6=T7
                $lichLamViecs = LichLamViec::where('bac_si_id', $bacSi->id)
                    ->where('ngay_trong_tuan', $weekday)
                    ->orderBy('thoi_gian_bat_dau')
                    ->get();

                // Gợi ý: Ca điều chỉnh (add) có thể mở thêm ca; nếu không có ca chính thức, check ca add
                $overridesAdd = CaDieuChinhBacSi::where('bac_si_id', $bacSi->id)
                    ->whereDate('ngay', $date->format('Y-m-d'))
                    ->where('hanh_dong', 'add')
                    ->get();

                if ($lichLamViecs->isEmpty() && $overridesAdd->isEmpty()) {
                    return response()->json([
                        'message' => 'Bác sĩ không làm việc vào ngày này',
                        'slots' => []
                    ]);
                }

                // Tổ hợp các ca làm việc/ca add (mỗi ca: start-end)
                $shifts = [];
                foreach ($lichLamViecs as $lv) {
                    $shifts[] = [
                        'start' => Carbon::parse($date->format('Y-m-d') . ' ' . $lv->thoi_gian_bat_dau),
                        'end' => Carbon::parse($date->format('Y-m-d') . ' ' . $lv->thoi_gian_ket_thuc),
                        'source' => 'schedule',
                    ];
                }
                foreach ($overridesAdd as $ov) {
                    $shifts[] = [
                        'start' => Carbon::parse($date->format('Y-m-d') . ' ' . $ov->gio_bat_dau),
                        'end' => Carbon::parse($date->format('Y-m-d') . ' ' . $ov->gio_ket_thuc),
                        'source' => 'override',
                    ];
                }

                // sort shifts by start time
                usort($shifts, function ($a, $b) { return $a['start']->lt($b['start']) ? -1 : 1; });

                // Booked appointments for the day (for overlap checks)
                $bookedAppointments = LichHen::where('bac_si_id', $bacSi->id)
                    ->where('ngay_hen', $date->format('Y-m-d'))
                    ->whereIn('trang_thai', [\App\Models\LichHen::STATUS_PENDING_VN, \App\Models\LichHen::STATUS_CONFIRMED_VN])
                    ->get();

                // Build available slots per shift
                $now = Carbon::now();
                $slots = [];
                foreach ($shifts as $shift) {
                    $current = $shift['start']->copy();
                    $shiftEnd = $shift['end']->copy();
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

                        // Kiểm tra LichNghi / CaDieuChinh removal overlap
                        $isRemoved = false;
                        $lichNghi = LichNghi::where('bac_si_id', $bacSi->id)->whereDate('ngay', $date->format('Y-m-d'))
                            ->where(function ($q) use ($current, $slotEnd) {
                                $q->where(function ($q2) use ($current, $slotEnd) {
                                    $q2->where('bat_dau', '<', $slotEnd->format('H:i:s'))
                                       ->where('ket_thuc', '>', $current->format('H:i:s'));
                                });
                            })->exists();

                        if ($lichNghi) $isRemoved = true;

                        // Check CaDieuChinh with 'remove' action
                        $overlapRemove = CaDieuChinhBacSi::where('bac_si_id', $bacSi->id)
                            ->whereDate('ngay', $date->format('Y-m-d'))
                            ->where('hanh_dong', 'remove')
                            ->where(function ($q) use ($current, $slotEnd) {
                                $q->where(function ($q2) use ($current, $slotEnd) {
                                    $q2->where('gio_bat_dau', '<', $slotEnd->format('H:i:s'))
                                       ->where('gio_ket_thuc', '>', $current->format('H:i:s'));
                                });
                            })->exists();

                        if ($overlapRemove) $isRemoved = true;

                        if ($isAvailable && !$isRemoved) {
                            $kLock = \App\Models\SlotLock::where('bac_si_id', $bacSi->id)
                                ->where('ngay', $date->format('Y-m-d'))
                                ->where('gio', $current->format('H:i'))
                                ->first();
                            $slots[] = [
                                'time' => $current->format('H:i'),
                                'label' => $current->format('H:i') . ' - ' . $slotEnd->format('H:i'),
                                'locked_by' => $kLock ? $kLock->user_id : null,
                                'locked_until' => $kLock ? $kLock->expires_at : null,
                            ];
                        }

                        $current->addMinutes(30); // Mỗi slot cách nhau 30 phút
                    }
                }

            return response()->json([
            'message' => 'Thành công',
            'slots' => $slots,
            'shifts' => array_map(function($s) {
                    return ['start' => optional($s['start'])->format('H:i') ?? null, 'end' => optional($s['end'])->format('H:i') ?? null];
                }, $shifts)
            ]);
    }

    // Trang lịch bệnh nhân: chọn bác sĩ, dịch vụ và ngày để xem slot trống
    public function publicCalendar()
    {
        $bacSis = BacSi::orderBy('ho_ten')->get(['id','ho_ten']);
        $dichVus = DichVu::orderBy('ten')->get(['id','ten','thoi_gian_uoc_tinh']);
        return view('public.lichhen.calendar', compact('bacSis','dichVus'));
    }

    /**
     * =====================================================
     * WORKFLOW METHODS - Theo quy trình nghiệp vụ y tế
     * =====================================================
     */

    /**
     * Bước 4: Bệnh nhân check-in khi đến phòng khám
     */
    public function checkIn(LichHen $lichHen)
    {
        // Kiểm tra quyền
        if ($lichHen->user_id !== Auth::id()) {
            return response()->json(['error' => 'Không có quyền'], 403);
        }

        $this->authorize('checkin', $lichHen);
        $result = $this->workflowService->checkIn($lichHen, auth()->user());

        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Check-in thành công! Vui lòng chờ bác sĩ gọi khám.',
                'status' => $lichHen->fresh()->trang_thai,
            ]);
        }

        return response()->json([
            'error' => 'Không thể check-in. Lịch hẹn chưa được xác nhận.',
        ], 400);
    }

    /**
     * Xem chi tiết lịch hẹn
     */
    public function show(LichHen $lichHen)
    {
        // Kiểm tra quyền
        if ($lichHen->user_id !== Auth::id()) {
            abort(403);
        }

        $lichHen->load(['bacSi.user', 'dichVu', 'hoaDon', 'benhAn']);

        return view('patient.lichhen.show', compact('lichHen'));
    }
}


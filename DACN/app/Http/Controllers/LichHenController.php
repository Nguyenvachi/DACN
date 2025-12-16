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
use App\Services\MedicalWorkflowService;
use App\Models\HoanTien;
use Illuminate\Support\Facades\Mail;
use App\Mail\HoaDonHoanTien;

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
        // CHỈ LẤY DỊCH VỤ CÔ BẢN VÀ ĐANG HOẠT ĐỘNG
        $danhSachDichVu = DichVu::where('loai', 'Cơ bản')
            ->where('hoat_dong', true)
            ->orderBy('ten_dich_vu')
            ->get();
        return view('public.lichhen.create', compact('bacSi', 'danhSachDichVu'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'bac_si_id' => 'required|exists:bac_sis,id',
                'dich_vu_id' => 'required|exists:dich_vus,id',
                'ngay_hen' => 'required|date_format:Y-m-d',
                'thoi_gian_hen' => 'required|date_format:H:i',
                'ghi_chu' => 'nullable|string|max:1000',
                'payment_method' => 'nullable|in:tien_mat,chuyen_khoan',
                'payment_gateway' => 'nullable|in:vnpay,momo',
                'ho_ten' => 'nullable|string|max:255',
                'so_dien_thoai' => 'nullable|string|max:20',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }

        $dichVu = DichVu::find($validatedData['dich_vu_id']);
        $duration = $dichVu ? (int) ($dichVu->thoi_gian_uoc_tinh ?? 30) : 30;

        $bacSiId = $validatedData['bac_si_id'];
        $date = $validatedData['ngay_hen'];
        $slotStart = Carbon::parse($date . ' ' . $validatedData['thoi_gian_hen']);
        $slotEnd = $slotStart->copy()->addMinutes($duration);

        // tính weekday (Carbon::dayOfWeek : 0=Sun..6=Sat)
        try {
            $dateCarbon = Carbon::parse($date);
            $weekday = $dateCarbon->dayOfWeek;
            $currentMonth = (int) $dateCarbon->format('n'); // Tháng hiện tại (1-12)
        } catch (\Exception $e) {
            $errorMsg = 'Ngày không hợp lệ';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $errorMsg], 422);
            }
            return redirect()->back()->withErrors(['ngay_hen' => $errorMsg])->withInput();
        }

        // Tìm lịch làm việc phù hợp: chỉ lấy lịch có chứa tháng hiện tại hoặc thangs = null
        $lichLamViec = LichLamViec::where('bac_si_id', $bacSiId)
            ->where('ngay_trong_tuan', $weekday)
            ->where(function ($q) use ($currentMonth) {
                $q->whereNull('thangs')
                    ->orWhereRaw('FIND_IN_SET(?, thangs) > 0', [$currentMonth]);
            })
            ->first();

        if (! $lichLamViec) {
            $errorMsg = 'Bác sĩ không làm việc vào ngày này (Tháng ' . $currentMonth . ')';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $errorMsg], 422);
            }
            return redirect()->back()->withErrors(['thoi_gian_hen' => $errorMsg])->withInput();
        }

        $shiftStart = Carbon::parse($date . ' ' . ($lichLamViec->thoi_gian_bat_dau ?? '08:00:00'));
        $shiftEnd   = Carbon::parse($date . ' ' . ($lichLamViec->thoi_gian_ket_thuc ?? '17:00:00'));

        if ($slotStart->lt($shiftStart) || $slotEnd->gt($shiftEnd)) {
            $errorMsg = "Khung giờ không hợp lệ: lịch hẹn kéo dài {$duration} phút và không nằm trong ca làm việc của bác sĩ";
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $errorMsg], 422);
            }
            return redirect()->back()->withErrors(['thoi_gian_hen' => $errorMsg])->withInput();
        }

        // ✅ KIỂM TRA GIỚI HẠN 2 SLOT/KHUNG GIỜ
        $sameTimeSlots = LichHen::where('bac_si_id', $bacSiId)
            ->where('ngay_hen', $date)
            ->where('thoi_gian_hen', $validatedData['thoi_gian_hen'])
            ->whereNotIn('trang_thai', [\App\Models\LichHen::STATUS_CANCELLED_VN])
            ->get();

        // Kiểm tra đã đủ 2 slot chưa
        if ($sameTimeSlots->count() >= 2) {
            $errorMsg = 'Khung giờ này đã đủ 2 người đặt. Vui lòng chọn khung giờ khác.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $errorMsg], 422);
            }
            return redirect()->back()->withErrors(['thoi_gian_hen' => $errorMsg])->withInput();
        }

        // ✅ CHỐNG SPAM: Kiểm tra user/người này đã đặt trong khung giờ này chưa
        $userId = auth()->id();
        $hoTen = $request->input('ho_ten');
        $soDienThoai = $request->input('so_dien_thoai');

        $existingBooking = $sameTimeSlots->first(function ($item) use ($userId, $hoTen, $soDienThoai) {
            // Nếu đã đăng nhập, check theo user_id
            if ($userId && $item->user_id == $userId) {
                return true;
            }
            // Nếu chưa đăng nhập, check theo số điện thoại
            if (!$userId && $soDienThoai && $item->so_dien_thoai_benh_nhan == $soDienThoai) {
                return true;
            }
            return false;
        });

        if ($existingBooking) {
            $errorMsg = 'Bạn đã đặt lịch trong khung giờ này rồi. Vui lòng chọn khung giờ khác.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $errorMsg], 422);
            }
            return redirect()->back()->withErrors(['thoi_gian_hen' => $errorMsg])->withInput();
        }

        // BỎ: Kiểm tra overlap phức tạp (không cần nữa vì đã giới hạn 2 slot/khung giờ)
        // Re-check overlaps theo duration hiện tại
        // $conflict = LichHen::where('bac_si_id', $bacSiId)
        //     ->where('ngay_hen', $date)
        //     ->get()
        //     ->filter(function ($item) use ($slotStart, $duration, $date) {
        //         $existStart = Carbon::parse($date . ' ' . $item->thoi_gian_hen);
        //         $existDur = $item->dichVu ? ($item->dichVu->thoi_gian_uoc_tinh ?? 30) : 30;
        //         $existEnd = $existStart->copy()->addMinutes($existDur);
        //         $reqStart = $slotStart->copy();
        //         $reqEnd = $reqStart->copy()->addMinutes($duration);
        //         return $reqStart->lt($existEnd) && $reqEnd->gt($existStart);
        //     })->isNotEmpty();

        // if ($conflict) {
        //     $errorMsg = 'Khung giờ đã bị đặt bởi người khác, vui lòng chọn khung giờ khác';
        //     if ($request->ajax() || $request->wantsJson()) {
        //         return response()->json(['success' => false, 'message' => $errorMsg], 422);
        //     }
        //     return redirect()->back()->withErrors(['thoi_gian_hen' => $errorMsg])->withInput();
        // }

        // ✅ KIỂM TRA XUNG ĐỘT BÁC SĨ (lịch nghỉ, ca điều chỉnh, lịch hẹn khác)
        $svc = app(LichKhamService::class);
        $doctorCheck = $svc->hasConflictForDoctor($bacSiId, $date, $validatedData['thoi_gian_hen'], $duration, null);
        if ($doctorCheck['conflict']) {
            $errorMsg = $doctorCheck['details'][0] ?? 'Khung giờ không khả dụng';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $errorMsg], 422);
            }
            return redirect()->back()->withErrors(['thoi_gian_hen' => $errorMsg])->withInput();
        }

        // ✅ KIỂM TRA XUNG ĐỘT BỆNH NHÂN (không cho đặt 2 lịch trùng giờ, kể cả với bác sĩ khác)
        $patientCheck = $svc->hasPatientConflict(Auth::id(), $date, $validatedData['thoi_gian_hen'], $duration, null);
        if ($patientCheck['conflict']) {
            $errorMsg = $patientCheck['details'][0] ?? 'Bạn đã có lịch hẹn trong khung giờ này';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $errorMsg], 422);
            }
            return redirect()->back()->withErrors(['thoi_gian_hen' => $errorMsg])->withInput();
        }

        // ✅ BỎ KIỂM TRA XUNG ĐỘT PHÒNG
        // Lý do: Nhiều bác sĩ có thể dùng chung phòng theo lịch làm việc
        // Chỉ cần kiểm tra xung đột lịch hẹn của cùng bác sĩ là đủ (đã check ở trên)

        // ✅ TẠO LỊCH HẸN TRONG TRANSACTION
        $lichHen = null;
        $hoaDon = null;
        $paymentMethod = $request->input('payment_method', 'tien_mat');
        $paymentGateway = $request->input('payment_gateway'); // vnpay hoặc momo

        DB::transaction(function () use ($validatedData, &$lichHen, &$hoaDon, $paymentMethod, $request) {
            // THÊM: Lấy giá dịch vụ để lưu vào tong_tien
            $dichVu = DichVu::find($validatedData['dich_vu_id']);
            $tongTien = $dichVu ? $dichVu->gia_tien : 0;

            $lichHen = LichHen::create([
                'user_id' => auth()->id(),
                'bac_si_id' => $validatedData['bac_si_id'],
                'dich_vu_id' => $validatedData['dich_vu_id'],
                'tong_tien' => $tongTien, // THÊM: Lưu giá tại thời điểm đặt
                'ngay_hen' => $validatedData['ngay_hen'],
                'thoi_gian_hen' => $validatedData['thoi_gian_hen'],
                'ghi_chu' => $validatedData['ghi_chu'] ?? null,
                'trang_thai' => \App\Models\LichHen::STATUS_PENDING_VN, // Trạng thái ban đầu
                'ho_ten_benh_nhan' => $request->input('ho_ten'),
                'so_dien_thoai_benh_nhan' => $request->input('so_dien_thoai'),
            ]);

            // THÊM: Tự động tạo hóa đơn luôn
            $hoaDon = HoaDon::create([
                'lich_hen_id' => $lichHen->id,
                'user_id' => auth()->id(),
                'tong_tien' => $tongTien,
                'trang_thai' => \App\Models\HoaDon::STATUS_UNPAID_VN,
                'phuong_thuc_thanh_toan' => $paymentMethod === 'chuyen_khoan' ? 'Chuyển khoản' : 'Tiền mặt',
            ]);
        });

        // Đảm bảo transaction đã tạo lịch hẹn và hóa đơn
        if (!$lichHen || !$hoaDon) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi tạo lịch hẹn'
                ], 500);
            }
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi tạo lịch hẹn')->withInput();
        }

        // Xử lý theo phương thức thanh toán
        if ($paymentMethod === 'chuyen_khoan') {
            // Nếu có chọn cổng thanh toán cụ thể (vnpay hoặc momo)
            if ($paymentGateway && in_array($paymentGateway, ['vnpay', 'momo'])) {
                // Nếu là AJAX request
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Đặt lịch thành công! Đang chuyển đến trang thanh toán...',
                        'payment_gateway' => $paymentGateway,
                        'hoa_don_id' => $hoaDon->id,
                        'amount' => $hoaDon->tong_tien,
                        'lich_hen_id' => $lichHen->id
                    ]);
                }
            }

            // Nếu chưa chọn cổng hoặc không phải AJAX, chuyển đến trang chọn cổng
            if ($request->ajax() || $request->wantsJson()) {
                $paymentUrl = route('patient.payment', $lichHen->id);
                return response()->json([
                    'success' => true,
                    'message' => 'Đặt lịch thành công!',
                    'payment_url' => $paymentUrl,
                    'lich_hen_id' => $lichHen->id
                ]);
            }

            // Nếu là form submit thông thường
            return redirect()->route('patient.payment', $lichHen->id)
                ->with('success', 'Đặt lịch thành công! Vui lòng thanh toán để hoàn tất.');
        }

        // Thanh toán tiền mặt
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đặt lịch thành công! Bạn sẽ thanh toán tiền mặt tại phòng khám.',
                'redirect_url' => route('public.bacsi.schedule', ['bacSi' => $bacSiId])
            ]);
        }

        return redirect()->route('public.bacsi.schedule', ['bacSi' => $bacSiId])
            ->with('success', 'Đặt lịch thành công! Bạn sẽ thanh toán tiền mặt tại phòng khám.');
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
        $dateCarbon = Carbon::parse($request->ngay_hen);
        $weekday = $dateCarbon->dayOfWeek;
        $currentMonth = (int) $dateCarbon->format('n');

        $lichLamViec = LichLamViec::where('bac_si_id', $lichHen->bac_si_id)
            ->where('ngay_trong_tuan', $weekday)
            ->where(function ($q) use ($currentMonth) {
                $q->whereNull('thangs')
                    ->orWhereRaw('FIND_IN_SET(?, thangs) > 0', [$currentMonth]);
            })
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
        }
        $lichHen->update([
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

        // Cập nhật trạng thái hủy
        $lichHen->update(['trang_thai' => \App\Models\LichHen::STATUS_CANCELLED_VN]);

        // Xử lý hoàn tiền tự động nếu đã thanh toán
        if ($lichHen->payment_status === 'Đã thanh toán' && $lichHen->hoaDon) {
            $hoaDon = $lichHen->hoaDon;
            $soTienHoan = $hoaDon->so_tien_da_thanh_toan - $hoaDon->so_tien_da_hoan;

            if ($soTienHoan > 0) {
                // Tạo yêu cầu hoàn tiền
                $hoanTien = HoanTien::create([
                    'hoa_don_id' => $hoaDon->id,
                    'so_tien' => $soTienHoan,
                    'ly_do' => 'Hủy lịch hẹn',
                    'trang_thai' => 'Đang xử lý',
                    'provider' => $hoaDon->phuong_thuc ?? 'CASH',
                    'provider_ref' => 'REFUND-' . now()->format('YmdHis') . '-' . $hoaDon->id,
                ]);

                // Cập nhật hóa đơn
                $hoaDon->so_tien_da_hoan += $soTienHoan;
                $hoaDon->trang_thai = 'Đã hủy';
                $hoaDon->save();

                // Gửi email thông báo
                try {
                    if ($lichHen->user && $lichHen->user->email) {
                        Mail::to($lichHen->user->email)->send(new HoaDonHoanTien($hoaDon, $hoanTien));
                    }
                } catch (\Exception $e) {
                    \Log::error('Send refund email failed: ' . $e->getMessage());
                }

                return redirect()->route('patient.lichhen.index')
                    ->with('success', 'Đã hủy lịch hẹn thành công. Yêu cầu hoàn tiền ' . number_format($soTienHoan) . ' VNĐ đang được xử lý.');
            }
        }

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

        // Cập nhật trạng thái hủy
        $lichHen->update(['trang_thai' => \App\Models\LichHen::STATUS_CANCELLED_VN]);

        // Xử lý hoàn tiền tự động nếu đã thanh toán
        if ($lichHen->payment_status === 'Đã thanh toán' && $lichHen->hoaDon) {
            $hoaDon = $lichHen->hoaDon;
            $soTienHoan = $hoaDon->so_tien_da_thanh_toan - $hoaDon->so_tien_da_hoan;

            if ($soTienHoan > 0) {
                // Tạo yêu cầu hoàn tiền
                $hoanTien = HoanTien::create([
                    'hoa_don_id' => $hoaDon->id,
                    'so_tien' => $soTienHoan,
                    'ly_do' => 'Hủy lịch hẹn',
                    'trang_thai' => 'Đang xử lý',
                    'provider' => $hoaDon->phuong_thuc ?? 'CASH',
                    'provider_ref' => 'REFUND-' . now()->format('YmdHis') . '-' . $hoaDon->id,
                ]);

                // Cập nhật hóa đơn
                $hoaDon->so_tien_da_hoan += $soTienHoan;
                $hoaDon->trang_thai = 'Đã hủy';
                $hoaDon->save();

                // Gửi email thông báo
                try {
                    if ($lichHen->user && $lichHen->user->email) {
                        Mail::to($lichHen->user->email)->send(new HoaDonHoanTien($hoaDon, $hoanTien));
                    }
                } catch (\Exception $e) {
                    \Log::error('Send refund email failed: ' . $e->getMessage());
                }

                return back()->with('success', 'Đã hủy lịch hẹn. Yêu cầu hoàn tiền ' . number_format($soTienHoan) . ' VNĐ đang được xử lý.');
            }
        }

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
            $query->whereHas('bacSi.lichLamViecs', function ($q) use ($phongId) {
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
            ->map(function ($lich) {
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
        $currentMonth = (int) $date->format('n'); // Tháng hiện tại (1-12)

        // Tìm lịch làm việc phù hợp: chỉ lấy lịch có chứa tháng hiện tại hoặc thangs = null (tất cả tháng)
        $lichLamViec = LichLamViec::where('bac_si_id', $bacSi->id)
            ->where('ngay_trong_tuan', $weekday)
            ->where(function ($q) use ($currentMonth) {
                $q->whereNull('thangs')
                    ->orWhereRaw('FIND_IN_SET(?, thangs) > 0', [$currentMonth]);
            })
            ->first();

        if (!$lichLamViec) {
            return response()->json([
                'message' => 'Bác sĩ không làm việc vào ngày này (Tháng ' . $currentMonth . ')',
                'slots' => []
            ]);
        }

        // Lấy giờ làm việc
        $shiftStart = Carbon::parse($date->format('Y-m-d') . ' ' . $lichLamViec->thoi_gian_bat_dau);
        $shiftEnd = Carbon::parse($date->format('Y-m-d') . ' ' . $lichLamViec->thoi_gian_ket_thuc);

        // Lấy danh sách lịch hẹn đã đặt trong ngày
        $bookedAppointments = LichHen::where('bac_si_id', $bacSi->id)
            ->where('ngay_hen', $date->format('Y-m-d'))
            ->whereIn('trang_thai', [\App\Models\LichHen::STATUS_PENDING_VN, \App\Models\LichHen::STATUS_CONFIRMED_VN])
            ->get();

        // Tạo danh sách khung giờ trống (mỗi 40 phút)
        $slotDuration = 40; // Mỗi slot kéo dài 40 phút
        $slots = [];
        $current = $shiftStart->copy();
        $now = Carbon::now();

        // Định nghĩa thời gian nghỉ
        $lunchBreakStart = Carbon::parse($date->format('Y-m-d') . ' 12:00');
        $lunchBreakEnd = Carbon::parse($date->format('Y-m-d') . ' 13:00');
        $dinnerBreakStart = Carbon::parse($date->format('Y-m-d') . ' 17:00');
        $dinnerBreakEnd = Carbon::parse($date->format('Y-m-d') . ' 18:00');

        while ($current->copy()->addMinutes($slotDuration)->lte($shiftEnd)) {
            $slotEnd = $current->copy()->addMinutes($slotDuration);

            // Chỉ hiển thị slot nếu thời gian bắt đầu >= thời gian hiện tại
            if ($current->lt($now)) {
                $current->addMinutes($slotDuration);
                continue;
            }

            // Bỏ qua khung giờ nghỉ trưa (12:00-13:00)
            if ($current->lt($lunchBreakEnd) && $slotEnd->gt($lunchBreakStart)) {
                // Nếu slot overlap với giờ nghỉ trưa, nhảy sang sau giờ nghỉ
                if ($current->lt($lunchBreakEnd)) {
                    $current = $lunchBreakEnd->copy();
                    continue;
                }
            }

            // Bỏ qua khung giờ nghỉ tối (17:00-18:00)
            if ($current->lt($dinnerBreakEnd) && $slotEnd->gt($dinnerBreakStart)) {
                // Nếu slot overlap với giờ nghỉ tối, nhảy sang sau giờ nghỉ
                if ($current->lt($dinnerBreakEnd)) {
                    $current = $dinnerBreakEnd->copy();
                    continue;
                }
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

            $current->addMinutes($slotDuration); // Mỗi slot cách nhau 40 phút
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
        $bacSis = BacSi::orderBy('ho_ten')->get(['id', 'ho_ten']);
        $dichVus = DichVu::where('loai', 'Cơ bản')
            ->where('hoat_dong', true)
            ->orderBy('ten_dich_vu')
            ->get(['id', 'ten_dich_vu as ten', 'thoi_gian_uoc_tinh']);
        return view('public.lichhen.calendar', compact('bacSis', 'dichVus'));
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

        $result = $this->workflowService->checkInAppointment($lichHen);

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

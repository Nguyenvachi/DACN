<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\LichHen;
use App\Models\HoaDon;
use Illuminate\Http\Request;

/**
 * Parent: app/Http/Controllers/Patient/
 * Child: PaymentController.php
 * Purpose: Xử lý thanh toán cho bệnh nhân
 */
class PaymentController extends Controller
{
    /**
     * Trang chọn phương thức thanh toán
     */
    public function show(LichHen $lichHen)
    {
        // Kiểm tra quyền: chỉ bệnh nhân của lịch hẹn mới xem được
        abort_unless($lichHen->user_id === auth()->id(), 403, 'Bạn không có quyền thanh toán lịch hẹn này');

        // SỬA: Eager load dichVu và bacSi để hiển thị đầy đủ thông tin
        $lichHen->load(['dichVu', 'bacSi', 'hoaDon']);

        // Lấy hóa đơn
        $hoaDon = $lichHen->hoaDon;

        if (!$hoaDon) {
            return redirect()->route('lichhen.my')->with('error', 'Không tìm thấy hóa đơn cho lịch hẹn này');
        }

        return view('public.lichhen.payment', compact('lichHen', 'hoaDon'));
    }

    /**
     * Bỏ qua thanh toán (thanh toán sau/tại phòng khám)
     * SỬA: Hủy lịch hẹn nếu không thanh toán ngay
     */
    public function skip(LichHen $lichHen)
    {
        abort_unless($lichHen->user_id === auth()->id(), 403);

        // THÊM: Hủy lịch hẹn và hóa đơn khi bỏ qua thanh toán
        $lichHen->update(['trang_thai' => \App\Models\LichHen::STATUS_CANCELLED_VN]);

        if ($lichHen->hoaDon) {
            $lichHen->hoaDon->update(['trang_thai' => \App\Models\HoaDon::STATUS_CANCELLED_VN]);
        }

        return redirect()->route('lichhen.my')->with('warning', 'Lịch hẹn đã bị hủy do chưa hoàn tất thanh toán. Vui lòng đặt lại lịch nếu cần.');
    }
}

<?php

namespace App\Observers;

use App\Models\LichHen;
use App\Models\HoaDon;
use Illuminate\Support\Facades\Mail;
use App\Mail\LichHenDaXacNhan;
use App\Mail\LichHenBiHuy;

class LichHenObserver
{
    public function updated(LichHen $lichHen): void
    {
        if ($lichHen->wasChanged('trang_thai')) {
            $newStatus = $lichHen->trang_thai;
            
            // Tạo hóa đơn khi xác nhận (nếu chưa có)
            if ($newStatus === 'Đã xác nhận') {
                if (! $lichHen->hoaDon) {
                    HoaDon::create([
                        'lich_hen_id' => $lichHen->id,
                        'user_id' => $lichHen->user_id,
                        'tong_tien' => $lichHen->tong_tien ?? $lichHen->tongTienDeXuat,
                        'trang_thai' => 'Chưa thanh toán',
                        'phuong_thuc' => null,
                        'ghi_chu' => 'Tự động tạo khi lịch hẹn được xác nhận',
                    ]);
                }
                if (is_null($lichHen->payment_status)) {
                    $lichHen->payment_status = 'Chưa thanh toán';
                    $lichHen->saveQuietly();
                }
                
                // THÊM: Gửi email xác nhận
                if ($lichHen->user && $lichHen->user->email) {
                    // SỬA: Eager load dichVu và bacSi trước khi gửi email
                    $lichHen->load(['dichVu', 'bacSi']);
                    Mail::to($lichHen->user->email)->queue(new LichHenDaXacNhan($lichHen));
                }
            }
            
            // THÊM: Gửi email khi hủy
            if ($newStatus === 'Đã hủy') {
                if ($lichHen->user && $lichHen->user->email) {
                    // SỬA: Eager load dichVu và bacSi trước khi gửi email
                    $lichHen->load(['dichVu', 'bacSi']);
                    Mail::to($lichHen->user->email)->queue(new LichHenBiHuy($lichHen, 'Lịch hẹn đã bị hủy'));
                }
            }
        }
    }
}

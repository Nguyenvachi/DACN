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

            // Tạo hóa đơn khi xác nhận (nếu chưa có) - GIỮ NGUYÊN TIẾNG VIỆT
                if ($newStatus === \App\Models\LichHen::STATUS_CONFIRMED_VN) {
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

                // ✅ Gửi email xác nhận (đồng bộ để tránh lỗi hiển thị)
                if ($lichHen->user && $lichHen->user->email) {
                    try {
                        $lichHen->load(['dichVu', 'bacSi', 'bacSi.chuyenKhoas']);
                        Mail::to($lichHen->user->email)->send(new LichHenDaXacNhan($lichHen));
                    } catch (\Exception $e) {
                        \Log::warning('Failed to send confirmation email: ' . $e->getMessage());
                    }
                }
            }

            // ✅ Gửi email khi hủy (đồng bộ để tránh lỗi hiển thị)
                if ($newStatus === \App\Models\LichHen::STATUS_CANCELLED_VN) {
                if ($lichHen->user && $lichHen->user->email) {
                    try {
                        $lichHen->load(['dichVu', 'bacSi', 'bacSi.chuyenKhoas']);
                        Mail::to($lichHen->user->email)->send(new LichHenBiHuy($lichHen, 'Lịch hẹn đã bị hủy'));
                    } catch (\Exception $e) {
                        \Log::warning('Failed to send cancellation email: ' . $e->getMessage());
                    }
                }
            }
        }
    }
}

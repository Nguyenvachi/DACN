<?php

namespace App\Observers;

use App\Models\LichHen;
use App\Models\TaiKham;
use App\Models\HoaDon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\LichHenDaXacNhan;
use App\Mail\LichHenBiHuy;
use App\Mail\LichHenCheckIn;
use App\Mail\LichHenHoanThanh;
use App\Observers\BenhAnObserver;

class LichHenObserver
{
    public function updated(LichHen $lichHen): void
    {
        // ✅ Thêm logging chi tiết cho debug
        Log::info('LichHenObserver triggered', [
            'lich_hen_id' => $lichHen->id,
            'old_status' => $lichHen->getOriginal('trang_thai'),
            'new_status' => $lichHen->trang_thai,
            'user_id' => $lichHen->user_id,
        ]);

        if ($lichHen->wasChanged('trang_thai')) {
            $newStatus = $lichHen->trang_thai;

            // Sync trạng thái lịch hẹn -> tái khám (nếu có liên kết)
            try {
                $taiKham = TaiKham::where('lich_hen_id', $lichHen->id)->first();
                if ($taiKham) {
                    $before = $taiKham->toArray();
                    if ($newStatus === \App\Models\LichHen::STATUS_COMPLETED_VN && $taiKham->trang_thai !== TaiKham::STATUS_COMPLETED_VN) {
                        $taiKham->update(['trang_thai' => TaiKham::STATUS_COMPLETED_VN]);
                    }

                    if ($newStatus === \App\Models\LichHen::STATUS_CANCELLED_VN && $taiKham->trang_thai !== TaiKham::STATUS_CANCELLED_VN) {
                        $taiKham->update(['trang_thai' => TaiKham::STATUS_CANCELLED_VN]);
                    }

                    if ($taiKham->wasChanged('trang_thai') && $taiKham->benhAn) {
                        BenhAnObserver::logCustomActionWithValues(
                            $taiKham->benhAn,
                            'tai_kham_synced_from_lich_hen',
                            ['tai_kham' => $before, 'lich_hen' => ['trang_thai' => $lichHen->getOriginal('trang_thai')]],
                            ['tai_kham' => $taiKham->toArray(), 'lich_hen' => ['trang_thai' => $newStatus]]
                        );
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Failed to sync TaiKham from LichHen status', [
                    'lich_hen_id' => $lichHen->id,
                    'error' => $e->getMessage(),
                ]);
            }

            // Tạo hóa đơn khi xác nhận (nếu chưa có) - GIỮ NGUYÊN TIẾNG VIỆT
                if ($newStatus === \App\Models\LichHen::STATUS_CONFIRMED_VN) {
                // ✅ Thêm kiểm tra trùng lặp hóa đơn
                if (!$lichHen->hoaDon) {
                    Log::info('Creating HoaDon for LichHen', ['lich_hen_id' => $lichHen->id]);
                    HoaDon::create([
                        'lich_hen_id' => $lichHen->id,
                        'user_id' => $lichHen->user_id,
                        'tong_tien' => $lichHen->tong_tien ?? $lichHen->tongTienDeXuat,
                        'trang_thai' => HoaDon::STATUS_UNPAID_VN,
                        'phuong_thuc' => null,
                        'ghi_chu' => 'Tự động tạo khi lịch hẹn được xác nhận',
                    ]);
                } else {
                    Log::warning('HoaDon already exists for LichHen', ['lich_hen_id' => $lichHen->id]);
                }
                if (is_null($lichHen->payment_status)) {
                    $lichHen->payment_status = HoaDon::STATUS_UNPAID_VN;
                    $lichHen->saveQuietly();
                }

                // ✅ Gửi email xác nhận (đồng bộ để tránh lỗi hiển thị)
                if ($lichHen->user && $lichHen->user->email) {
                    try {
                        $lichHen->load(['dichVu', 'bacSi', 'bacSi.chuyenKhoas']);
                        Mail::to($lichHen->user->email)->send(new LichHenDaXacNhan($lichHen));
                    } catch (\Exception $e) {
                        Log::warning('Failed to send confirmation email: ' . $e->getMessage());
                    }
                }
            }

            // ✅ Thêm xử lý cho trạng thái "Đã check-in" - Gửi email thông báo check-in thành công
            if ($newStatus === \App\Models\LichHen::STATUS_CHECKED_IN_VN) {
                if ($lichHen->user && $lichHen->user->email) {
                    try {
                        $lichHen->load(['dichVu', 'bacSi', 'bacSi.chuyenKhoas']);
                        // Giả sử bạn có Mail class cho check-in, nếu chưa có thì tạo mới (file con: app/Mail/LichHenCheckIn.php)
                        Mail::to($lichHen->user->email)->send(new LichHenCheckIn($lichHen));
                    } catch (\Exception $e) {
                        Log::warning('Failed to send check-in email: ' . $e->getMessage());
                    }
                }
            }

            // ✅ Thêm xử lý cho trạng thái "Hoàn thành" - Gửi email tóm tắt và cập nhật hóa đơn
            if ($newStatus === \App\Models\LichHen::STATUS_COMPLETED_VN) {
                // Cập nhật hóa đơn nếu có (đảm bảo trạng thái đồng bộ)
                if ($lichHen->hoaDon) {
                    $lichHen->hoaDon->update(['ghi_chu' => 'Khám hoàn thành - ' . ($lichHen->hoaDon->ghi_chu ?? '')]);
                }
                // Gửi email tóm tắt cho bệnh nhân
                if ($lichHen->user && $lichHen->user->email) {
                    try {
                        $lichHen->load(['dichVu', 'bacSi', 'bacSi.chuyenKhoas', 'benhAn']);
                        // Giả sử bạn có Mail class cho hoàn thành, nếu chưa có thì tạo mới (file con: app/Mail/LichHenHoanThanh.php)
                        Mail::to($lichHen->user->email)->send(new LichHenHoanThanh($lichHen));
                    } catch (\Exception $e) {
                        Log::warning('Failed to send completion email: ' . $e->getMessage());
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
                        Log::warning('Failed to send cancellation email: ' . $e->getMessage());
                    }
                }
            }
        }
    }
}

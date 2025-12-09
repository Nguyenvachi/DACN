<?php

namespace App\Console\Commands;

use App\Models\LichHen;
use App\Models\HoaDon;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Parent: app/Console/Commands/
 * Child: CancelUnpaidAppointments.php
 * Purpose: Tự động hủy các lịch hẹn chưa thanh toán sau 15 phút
 */
class CancelUnpaidAppointments extends Command
{
    protected $signature = 'appointments:cancel-unpaid';
    protected $description = 'Tự động hủy các lịch hẹn chưa thanh toán sau 15 phút';

    public function handle()
    {
        // Lấy các lịch hẹn: Chờ xác nhận + created > 15 phút + hóa đơn chưa thanh toán
        $timeout = Carbon::now()->subMinutes(15);

            $unpaidAppointments = LichHen::where('trang_thai', \App\Models\LichHen::STATUS_PENDING_VN)
            ->where('created_at', '<=', $timeout)
            ->whereHas('hoaDon', function($query) {
                $query->where('trang_thai', \App\Models\HoaDon::STATUS_UNPAID_VN);
            })
            ->get();

        $canceledCount = 0;

        foreach ($unpaidAppointments as $lichHen) {
            try {
                // Hủy lịch hẹn
                    $lichHen->update(['trang_thai' => \App\Models\LichHen::STATUS_CANCELLED_VN]);

                // Hủy hóa đơn
                if ($lichHen->hoaDon) {
                    $lichHen->hoaDon->update(['trang_thai' => \App\Models\HoaDon::STATUS_CANCELLED_VN]);
                }

                $canceledCount++;

                Log::info("Tự động hủy lịch hẹn #{$lichHen->id} do quá thời gian thanh toán");

            } catch (\Exception $e) {
                Log::error("Lỗi khi hủy lịch hẹn #{$lichHen->id}: " . $e->getMessage());
            }
        }

        $this->info("Đã hủy {$canceledCount} lịch hẹn chưa thanh toán");

        return 0;
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LichHen;
use App\Notifications\AppointmentReminder;
use Carbon\Carbon;

class TestSendReminder extends Command
{
    protected $signature = 'test:send-reminder {lichHenId?}';
    protected $description = 'Test gửi mail nhắc lịch hẹn';

    public function handle()
    {
        $lichHenId = $this->argument('lichHenId');

        if ($lichHenId) {
            $lichHen = LichHen::with(['user', 'bacSi', 'dichVu'])->find($lichHenId);
            if (!$lichHen) {
                $this->error("Không tìm thấy lịch hẹn ID: {$lichHenId}");
                return 1;
            }

            if (!$lichHen->user) {
                $this->error("Lịch hẹn #{$lichHenId} không có user!");
                return 1;
            }

            if (!$lichHen->user->email) {
                $this->error("User #{$lichHen->user_id} không có email!");
                return 1;
            }

            $this->info("Đang gửi mail đến: {$lichHen->user->email}");
            $this->info("Lịch hẹn: {$lichHen->ngay_hen} {$lichHen->thoi_gian_hen}");
            $this->info("Bác sĩ: " . optional($lichHen->bacSi)->ho_ten);

            // Gửi ngay không qua queue
            $lichHen->user->notify(new AppointmentReminder($lichHen, 'TEST', true));

            $this->info("✓ Đã gửi mail thành công!");
            return 0;
        }

        // Không có ID -> tìm lịch hẹn gần nhất có user
        $lichHen = LichHen::with(['user', 'bacSi', 'dichVu'])
            ->whereHas('user', function($q) {
                $q->whereNotNull('email');
            })
            ->whereIn('trang_thai', [\App\Models\LichHen::STATUS_PENDING_VN, \App\Models\LichHen::STATUS_CONFIRMED_VN])
            ->whereDate('ngay_hen', '>=', Carbon::today())
            ->orderBy('ngay_hen')
            ->orderBy('thoi_gian_hen')
            ->first();

        if (!$lichHen) {
            $this->error("Không tìm thấy lịch hẹn nào phù hợp!");
            return 1;
        }

        $this->info("Đang gửi mail đến: {$lichHen->user->email}");
        $this->info("Lịch hẹn #{$lichHen->id}: {$lichHen->ngay_hen} {$lichHen->thoi_gian_hen}");
        $this->info("Bác sĩ: " . optional($lichHen->bacSi)->ho_ten);

        // Gửi ngay không qua queue
        $lichHen->user->notify(new AppointmentReminder($lichHen, 'TEST', true));

        $this->info("✓ Đã gửi mail thành công!");
        return 0;
    }
}

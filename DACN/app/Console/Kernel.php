<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Cache;
use App\Models\LichHen;
use App\Notifications\AppointmentReminder;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\LinkDoctors::class,
        \App\Console\Commands\TestSendReminder::class,
        \App\Console\Commands\CancelUnpaidAppointments::class, // THÊM: Command tự động hủy lịch chưa thanh toán
        \App\Console\Commands\PublishScheduledPosts::class, // THÊM: Command tự động publish bài viết
        \App\Console\Commands\SyncPermissionsLang::class, // THÊM: Sync permissions -> resources/lang/vi
    ];

    protected function schedule(Schedule $schedule): void
    {
        // Nhắc lịch T-24h mỗi 15 phút (chỉ thêm, không sửa bỏ gì)
        $schedule->call(function(){
            $now = Carbon::now();
            $targetDate = $now->copy()->addDay()->toDateString();
            $candidates = LichHen::with(['user','bacSi','dichVu'])
                ->whereIn('trang_thai', [\App\Models\LichHen::STATUS_PENDING_VN, \App\Models\LichHen::STATUS_CONFIRMED_VN])
                ->whereDate('ngay_hen', $targetDate)
                ->get();
            foreach ($candidates as $lh) {
                $dateOnly = $lh->ngay_hen instanceof \Carbon\Carbon ? $lh->ngay_hen->format('Y-m-d') : (string)$lh->ngay_hen;
                $appt = Carbon::parse($dateOnly . ' ' . ($lh->thoi_gian_hen ?? '00:00:00'));
                $mins = $now->diffInMinutes($appt, false); // phút đến lịch hẹn
                if ($mins <= 1440 && $mins >= 1420) { // cửa sổ ~20 phút quanh 24h
                    $key = 'reminder:lich_hen:'.$lh->id.':24h';
                    if (Cache::add($key, 1, now()->addDay())) {
                        optional($lh->user)->notify(new AppointmentReminder($lh, '24h'));
                    }
                }
            }
        })->everyFifteenMinutes();

        // Nhắc lịch T-3h mỗi 10 phút
        $schedule->call(function(){
            $now = Carbon::now();
            $targetDate = $now->copy()->addHours(3)->toDateString();
            $candidates = LichHen::with(['user','bacSi','dichVu'])
                ->whereIn('trang_thai', [\App\Models\LichHen::STATUS_PENDING_VN, \App\Models\LichHen::STATUS_CONFIRMED_VN])
                ->whereDate('ngay_hen', $targetDate)
                ->get();
            foreach ($candidates as $lh) {
                $dateOnly = $lh->ngay_hen instanceof \Carbon\Carbon ? $lh->ngay_hen->format('Y-m-d') : (string)$lh->ngay_hen;
                $appt = Carbon::parse($dateOnly . ' ' . ($lh->thoi_gian_hen ?? '00:00:00'));
                $mins = $now->diffInMinutes($appt, false);
                if ($mins <= 180 && $mins >= 160) { // cửa sổ ~20 phút quanh 3h
                    $key = 'reminder:lich_hen:'.$lh->id.':3h';
                    if (Cache::add($key, 1, now()->addHours(6))) {
                        optional($lh->user)->notify(new AppointmentReminder($lh, '3h'));
                    }
                }
            }
        })->everyTenMinutes();

        // THÊM: Tự động hủy lịch hẹn chưa thanh toán sau 15 phút
        $schedule->command('appointments:cancel-unpaid')->everyFiveMinutes();

        // Kiểm tra thuốc hết hạn mỗi ngày lúc 8:00 sáng
        $schedule->job(new \App\Jobs\CheckThuocHetHan)->dailyAt('08:00');

        // Kiểm tra thuốc giảm tồn mỗi ngày lúc 9:00 sáng
        $schedule->job(new \App\Jobs\CheckThuocGiamTon)->dailyAt('09:00');

        // THÊM: Tự động publish bài viết đã đến giờ mỗi 10 phút
        $schedule->command('posts:publish-scheduled')->everyTenMinutes();
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
    }
}

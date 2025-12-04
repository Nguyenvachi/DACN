<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\LichHen;
use App\Notifications\AppointmentReminder;
use Carbon\Carbon;

class ReminderController extends Controller
{
    // Gửi nhắc lịch cho tất cả lịch hẹn của ngày mai (Admin)
    public function sendTomorrow(Request $request)
    {
        $tomorrow = Carbon::tomorrow()->toDateString();
        $force = $request->boolean('force', false);

        $candidates = LichHen::with(['user','bacSi','dichVu'])
            ->whereIn('trang_thai', ['Chờ xác nhận','Đã xác nhận'])
            ->whereDate('ngay_hen', $tomorrow)
            ->get();

        $sent = 0;
        foreach ($candidates as $lh) {
            if ($force) {
                optional($lh->user)->notify(new AppointmentReminder($lh, '24h'));
                $sent++;
                continue;
            }
            $key = 'reminder:lich_hen:'.$lh->id.':24h';
            if (Cache::add($key, 1, now()->addDay())) {
                optional($lh->user)->notify(new AppointmentReminder($lh, '24h'));
                $sent++;
            }
        }

        return back()->with('status', "Đã gửi {$sent}/{$candidates->count()} lịch hẹn ngày mai.");
    }

    // Gửi nhắc lịch T-3h cho các lịch hẹn trong 3 giờ tới
    public function sendNext3Hours(Request $request)
    {
        $now = Carbon::now();
        $end = $now->copy()->addHours(3);
        $force = $request->boolean('force', false);

        // Lấy các lịch từ hôm nay đến ngày mai để bắt trường hợp qua 0h
        $candidates = LichHen::with(['user','bacSi','dichVu'])
            ->whereIn('trang_thai', ['Chờ xác nhận','Đã xác nhận'])
            ->whereDate('ngay_hen', '>=', $now->toDateString())
            ->whereDate('ngay_hen', '<=', $end->toDateString())
            ->get();

        $total = $candidates->count();
        $sent = 0;
        foreach ($candidates as $lh) {
            // Sửa: Parse riêng ngày và giờ để tránh lỗi format
            $dateStr = $lh->ngay_hen instanceof \Carbon\Carbon
                ? $lh->ngay_hen->format('Y-m-d')
                : $lh->ngay_hen;

            $appt = Carbon::parse($dateStr . ' ' . $lh->thoi_gian_hen);
            $mins = $now->diffInMinutes($appt, false);
            if ($mins < 0 || $mins > 180) {
                // Chỉ gửi cho các lịch trong vòng 3 giờ tới
                continue;
            }
            if ($force) {
                optional($lh->user)->notify(new AppointmentReminder($lh, '3h'));
                $sent++;
                continue;
            }
            $key = 'reminder:lich_hen:'.$lh->id.':3h';
            if (Cache::add($key, 1, now()->addHours(6))) {
                optional($lh->user)->notify(new AppointmentReminder($lh, '3h'));
                $sent++;
            }
        }

        return back()->with('status', "Đã gửi {$sent}/{$total} lịch hẹn trong 3 giờ tới.");
    }
}

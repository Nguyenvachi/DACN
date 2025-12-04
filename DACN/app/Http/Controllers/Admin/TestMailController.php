<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LichHen;
use App\Notifications\AppointmentReminder;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TestMailController extends Controller
{
    public function index()
    {
        // Lấy tất cả lịch hẹn có user và email
        $lichHenList = LichHen::with(['user', 'bacSi', 'dichVu'])
            ->whereHas('user', function($q) {
                $q->whereNotNull('email');
            })
            ->whereDate('ngay_hen', '>=', Carbon::today())
            ->orderBy('ngay_hen')
            ->orderBy('thoi_gian_hen')
            ->limit(20)
            ->get();

        return view('admin.tools.test-mail', compact('lichHenList'));
    }

    public function send($id)
    {
        $lichHen = LichHen::with(['user', 'bacSi', 'dichVu'])->findOrFail($id);

        if (!$lichHen->user || !$lichHen->user->email) {
            return back()->with('error', 'Lịch hẹn này không có email hợp lệ!');
        }

        try {
            // Gửi mail sync (không qua queue)
            $lichHen->user->notify(new AppointmentReminder($lichHen, 'TEST', true));

            return back()->with('success', 
                "✓ Đã gửi mail test đến: {$lichHen->user->email} cho lịch hẹn #{$lichHen->id}. Vui lòng kiểm tra hộp thư!"
            );
        } catch (\Exception $e) {
            return back()->with('error', 
                "✗ Lỗi khi gửi mail: " . $e->getMessage()
            );
        }
    }
}

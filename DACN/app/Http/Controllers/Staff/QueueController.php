<?php

// File: app/Http/Controllers/Staff/QueueController.php
// Parent: app/Http/Controllers/Staff/

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\LichHen;
use Illuminate\Http\Request;
use Carbon\Carbon;

class QueueController extends Controller
{
    /**
     * Display queue management page
     */
    public function index()
    {
        $today = Carbon::today();

        // Get checked-in appointments waiting for examination
        $queue = LichHen::with(['user', 'bacSi', 'dichVu'])
            ->whereDate('ngay_hen', $today)
            ->where('trang_thai', 'Đã check-in')
            ->orderBy('thoi_gian_hen', 'asc')
            ->orderBy('checked_in_at', 'asc')
            ->get();

        // Get in-progress appointments
        $inProgress = LichHen::with(['user', 'bacSi', 'dichVu'])
            ->whereDate('ngay_hen', $today)
            ->where('trang_thai', 'Đang khám')
            ->orderBy('thoi_gian_hen', 'asc')
            ->get();

        // Get completed today
        $completed = LichHen::with(['user', 'bacSi', 'dichVu'])
            ->whereDate('ngay_hen', $today)
            ->where('trang_thai', 'Hoàn thành')
            ->orderByDesc('updated_at')
            ->limit(10)
            ->get();

        $statistics = [
            'waiting' => $queue->count(),
            'in_progress' => $inProgress->count(),
            'completed_today' => LichHen::whereDate('ngay_hen', $today)->where('trang_thai', 'Hoàn thành')->count(),
            'avg_wait_time' => $this->calculateAverageWaitTime($today)
        ];

        return view('staff.queue.index', compact('queue', 'inProgress', 'completed', 'statistics'));
    }

    /**
     * Call next patient for examination
     */
    public function callNext(LichHen $lichhen)
    {
        if ($lichhen->trang_thai !== 'Đã check-in') {
            return back()->with('error', 'Chỉ có thể gọi bệnh nhân đã check-in vào khám.');
        }

        $lichhen->update([
            'trang_thai' => 'Đang khám',
            'thoi_gian_bat_dau_kham' => now()
        ]);

        activity()
            ->performedOn($lichhen)
            ->causedBy(auth()->user())
            ->withProperties(['action' => 'call_next'])
            ->log('Nhân viên gọi bệnh nhân vào khám');

        return back()->with('success', "Đã gọi bệnh nhân {$lichhen->user->name} vào khám với BS. {$lichhen->bacSi->ho_ten}");
    }

    /**
     * Get realtime queue data for auto-refresh
     */
    public function realtimeData()
    {
        $today = Carbon::today();

        $waiting = LichHen::whereDate('ngay_hen', $today)
            ->where('trang_thai', 'Đã check-in')
            ->count();

        $inProgress = LichHen::whereDate('ngay_hen', $today)
            ->where('trang_thai', 'Đang khám')
            ->count();

        $completed = LichHen::whereDate('ngay_hen', $today)
            ->where('trang_thai', 'Hoàn thành')
            ->count();

        // Get latest queue list
        $queue = LichHen::with(['user', 'bacSi'])
            ->whereDate('ngay_hen', $today)
            ->where('trang_thai', 'Đã check-in')
            ->orderBy('thoi_gian_hen', 'asc')
            ->orderBy('checked_in_at', 'asc')
            ->get()
            ->map(function($apt, $index) {
                return [
                    'id' => $apt->id,
                    'queue_number' => $index + 1,
                    'patient_name' => $apt->user->name,
                    'doctor_name' => $apt->bacSi->ho_ten,
                    'check_in_time' => $apt->checked_in_at ? Carbon::parse($apt->checked_in_at)->format('H:i') : null,
                    'wait_duration' => $apt->checked_in_at ? Carbon::parse($apt->checked_in_at)->diffInMinutes(now()) : 0
                ];
            });

        return response()->json([
            'statistics' => [
                'waiting' => $waiting,
                'in_progress' => $inProgress,
                'completed' => $completed
            ],
            'queue' => $queue,
            'timestamp' => now()->toIso8601String()
        ]);
    }

    /**
     * Calculate average wait time for today
     */
    private function calculateAverageWaitTime($date)
    {
        $completed = LichHen::whereDate('ngay_hen', $date)
            ->where('trang_thai', 'Hoàn thành')
            ->whereNotNull('checked_in_at')
            ->whereNotNull('completed_at')
            ->get();

        if ($completed->isEmpty()) {
            return 0;
        }

        $totalWaitMinutes = $completed->sum(function($apt) {
            $checkIn = Carbon::parse($apt->checked_in_at);
            $completedAt = Carbon::parse($apt->completed_at);
            return $checkIn->diffInMinutes($completedAt);
        });

        return round($totalWaitMinutes / $completed->count());
    }
}

<?php

// File: app/Http/Controllers/Staff/CheckInController.php
// Parent: app/Http/Controllers/Staff/

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\LichHen;
use App\Services\MedicalWorkflowService;
use Illuminate\Http\Request;
use Carbon\Carbon;
 // Note: activity() helper is optional (spatie/activitylog). Use function_exists checks to guard.

class CheckInController extends Controller
{
    /**
     * Display check-in page with today's appointments
     */
    public function index(Request $request)
    {
        $today = Carbon::today();
        $search = $request->get('search');

        // Hiển thị lịch hẹn từ hôm nay đến 7 ngày tới để có thể check-in sớm
        $query = LichHen::with(['user', 'bacSi', 'dichVu'])
            ->whereDate('ngay_hen', '>=', $today)
            ->whereDate('ngay_hen', '<=', $today->copy()->addDays(7))
            ->whereIn('trang_thai', [\App\Models\LichHen::STATUS_CONFIRMED_VN, \App\Models\LichHen::STATUS_IN_PROGRESS_VN, \App\Models\LichHen::STATUS_CHECKED_IN_VN]);

        if ($search) {
            $query->whereHas('user', function($subQ) use ($search) {
                $subQ->where('name', 'LIKE', "%{$search}%")
                     ->orWhere('email', 'LIKE', "%{$search}%")
                     ->orWhere('so_dien_thoai', 'LIKE', "%{$search}%");
            });
        }

        $appointments = $query->orderBy('ngay_hen', 'asc')->orderBy('thoi_gian_hen', 'asc')->paginate(20);

        $statistics = [
            'total' => LichHen::whereDate('ngay_hen', '>=', $today)->whereDate('ngay_hen', '<=', $today->copy()->addDays(7))->whereIn('trang_thai', [\App\Models\LichHen::STATUS_CONFIRMED_VN, \App\Models\LichHen::STATUS_IN_PROGRESS_VN, \App\Models\LichHen::STATUS_CHECKED_IN_VN])->count(),
            'checked_in' => LichHen::whereDate('ngay_hen', '>=', $today)->whereDate('ngay_hen', '<=', $today->copy()->addDays(7))->where('trang_thai', \App\Models\LichHen::STATUS_CHECKED_IN_VN)->count(),
            'waiting' => LichHen::whereDate('ngay_hen', '>=', $today)->whereDate('ngay_hen', '<=', $today->copy()->addDays(7))->where('trang_thai', \App\Models\LichHen::STATUS_CONFIRMED_VN)->count(),
            'in_progress' => LichHen::whereDate('ngay_hen', '>=', $today)->whereDate('ngay_hen', '<=', $today->copy()->addDays(7))->where('trang_thai', \App\Models\LichHen::STATUS_IN_PROGRESS_VN)->count(),
        ];

        return view('staff.checkin.index', compact('appointments', 'statistics', 'search'));
    }

    /**
     * Process check-in for patient
     */
    public function checkIn(Request $request, LichHen $lichhen, MedicalWorkflowService $workflowService)
    {
        // Authorization: only staff/admin allowed via policy
        $this->authorize('checkin', $lichhen);

        // Call workflow service - it will validate and set timestamps
        try {
            $ok = $workflowService->checkIn($lichhen, auth()->user());
            if (! $ok) {
                return back()->with('error', 'Lịch hẹn này không thể check-in.');
            }
            return back()->with('success', "Đã check-in thành công cho bệnh nhân {$lichhen->user->name}");
        } catch (\Throwable $e) {
            \Log::error('CheckIn failed: ' . $e->getMessage());
            return back()->with('error', 'Không thể check-in: ' . $e->getMessage());
        }
    }

    /**
     * Quick search for patient by code or phone
     */
    public function quickSearch(Request $request)
    {
        $keyword = $request->get('keyword');

        if (!$keyword) {
            return response()->json(['error' => 'Vui lòng nhập mã lịch hẹn hoặc số điện thoại'], 400);
        }

        $appointment = LichHen::with(['user', 'bacSi', 'dichVu'])
            ->whereHas('user', function($subQ) use ($keyword) {
                $subQ->where('so_dien_thoai', $keyword);
            })
            ->whereDate('ngay_hen', '>=', Carbon::today())
            ->whereDate('ngay_hen', '<=', Carbon::today()->addDays(7))
            ->whereIn('trang_thai', [\App\Models\LichHen::STATUS_CONFIRMED_VN, \App\Models\LichHen::STATUS_CHECKED_IN_VN])
            ->first();

        if (!$appointment) {
            return response()->json(['error' => 'Không tìm thấy lịch hẹn hôm nay với thông tin này'], 404);
        }

        return response()->json([
            'success' => true,
            'appointment' => [
                'id' => $appointment->id,
                'patient_name' => $appointment->user->name,
                'patient_phone' => $appointment->user->so_dien_thoai,
                'doctor_name' => $appointment->bacSi->ho_ten,
                'service_name' => $appointment->dichVu->ten_dich_vu,
                'time' => $appointment->thoi_gian_hen,
                'status' => $appointment->trang_thai,
                'can_checkin' => $appointment->trang_thai === \App\Models\LichHen::STATUS_CONFIRMED_VN
            ]
        ]);
    }

    /**
     * Bulk check-in for multiple appointments
     */
    public function bulkCheckIn(Request $request, MedicalWorkflowService $workflowService)
    {
        $request->validate([
            'appointment_ids' => 'required|array',
            'appointment_ids.*' => 'exists:lich_hens,id'
        ]);

        $updated = 0;
        $failed = [];

        foreach ($request->appointment_ids as $id) {
            $lichhen = LichHen::find($id);

            if ($lichhen) {
                try {
                    $ok = $workflowService->checkIn($lichhen, auth()->user());
                    if ($ok) {
                        $updated++;
                        continue;
                    }
                } catch (\Throwable $e) {
                    \Log::warning('Bulk check-in failed for ' . $id . ': ' . $e->getMessage());
                }
            }
            $failed[] = "ID: {$id}";
        }

        $message = "Đã check-in thành công {$updated} lịch hẹn.";
        if (count($failed) > 0) {
            $message .= " Không thể check-in: " . implode(', ', $failed);
        }

        if ($updated > 0) {
            $this->logBulkCheckinActivity($request->appointment_ids, auth()->user());
        }

        return back()->with('success', $message);
    }

    protected function logBulkCheckinActivity(array $ids, $user)
    {
            if (function_exists('activity')) {
                try {
                    call_user_func('activity')->causedBy($user)->withProperties(['ids' => $ids])->log('Nhân viên check-in hàng loạt');
                } catch (\Throwable $e) {
                    \Log::warning('Activity log bulk check-in failed: ' . $e->getMessage());
                }
            }
    }
}

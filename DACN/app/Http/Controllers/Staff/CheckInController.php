<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\LichHen;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CheckInController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        $search = $request->get('search');

        $query = LichHen::with(['user', 'bacSi.phong', 'dichVu'])
            ->whereIn('trang_thai', [\App\Models\LichHen::STATUS_CONFIRMED_VN, \App\Models\LichHen::STATUS_IN_PROGRESS_VN, \App\Models\LichHen::STATUS_CHECKED_IN_VN]);

        if ($search) {
            $query->whereHas('user', function ($subQ) use ($search) {
                $subQ->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('so_dien_thoai', 'LIKE', "%{$search}%");
            });
        }

        $appointments = $query->orderBy('ngay_hen', 'desc')->orderBy('thoi_gian_hen', 'asc')->paginate(20);

        $statistics = [
            'total' => LichHen::whereIn('trang_thai', [\App\Models\LichHen::STATUS_CONFIRMED_VN, \App\Models\LichHen::STATUS_IN_PROGRESS_VN, \App\Models\LichHen::STATUS_CHECKED_IN_VN])->count(),
            'checked_in' => LichHen::where('trang_thai', \App\Models\LichHen::STATUS_CHECKED_IN_VN)->count(),
            'waiting' => LichHen::where('trang_thai', \App\Models\LichHen::STATUS_CONFIRMED_VN)->count(),
            'in_progress' => LichHen::where('trang_thai', \App\Models\LichHen::STATUS_IN_PROGRESS_VN)->count(),
        ];

        return view('staff.checkin.index', compact('appointments', 'statistics', 'search'));
    }

    public function checkIn(Request $request, $lichhen)
    {
        try {
            $lichHen = LichHen::findOrFail($lichhen);

            if ($lichHen->trang_thai !== \App\Models\LichHen::STATUS_CONFIRMED_VN) {
                return back()->with('error', 'Chỉ có thể check-in lịch hẹn đã xác nhận.');
            }

            if ($lichHen->bacSi->phong_id) {
                $maxStt = LichHen::join('bac_sis', 'lich_hens.bac_si_id', '=', 'bac_sis.id')
                    ->where('bac_sis.phong_id', $lichHen->bacSi->phong_id)
                    ->whereDate('lich_hens.ngay_hen', $lichHen->ngay_hen)
                    ->whereNotNull('lich_hens.stt_kham')
                    ->max('lich_hens.stt_kham');
            } else {
                $maxStt = LichHen::where('bac_si_id', $lichHen->bac_si_id)
                    ->whereDate('ngay_hen', $lichHen->ngay_hen)
                    ->whereNotNull('stt_kham')
                    ->max('stt_kham');
            }

            $nextStt = ($maxStt ?? 0) + 1;

            $lichHen->update([
                'trang_thai' => \App\Models\LichHen::STATUS_CHECKED_IN_VN,
                'stt_kham' => $nextStt,
                'checked_in_at' => now()
            ]);

            $roomInfo = $lichHen->bacSi->phong ? " - Phòng {$lichHen->bacSi->phong->ten}" : '';
            return back()->with('success', "Đã check-in thành công cho bệnh nhân {$lichHen->user->name}. STT khám: {$nextStt}{$roomInfo}");
        } catch (\Exception $e) {
            \Log::error('CheckIn error: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi check-in: ' . $e->getMessage());
        }
    }

    public function quickSearch(Request $request)
    {
        $keyword = $request->get('keyword');

        if (!$keyword) {
            return response()->json(['error' => 'Vui lòng nhập mã lịch hẹn hoặc số điện thoại'], 400);
        }

        $appointment = LichHen::with(['user', 'bacSi', 'dichVu'])
            ->whereHas('user', function ($subQ) use ($keyword) {
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

    public function bulkCheckIn(Request $request)
    {
        $request->validate([
            'appointment_ids' => 'required|array',
            'appointment_ids.*' => 'exists:lich_hens,id'
        ]);

        $updated = 0;
        $failed = [];

        foreach ($request->appointment_ids as $id) {
            $lichhen = LichHen::find($id);

            if ($lichhen && $lichhen->trang_thai === \App\Models\LichHen::STATUS_CONFIRMED_VN && Carbon::parse($lichhen->ngay_hen)->isToday()) {
                $maxStt = LichHen::where('bac_si_id', $lichhen->bac_si_id)
                    ->whereDate('ngay_hen', $lichhen->ngay_hen)
                    ->whereNotNull('stt_kham')
                    ->max('stt_kham');

                $lichhen->update([
                    'trang_thai' => \App\Models\LichHen::STATUS_CHECKED_IN_VN,
                    'stt_kham' => ($maxStt ?? 0) + 1,
                    'checked_in_at' => now()
                ]);
                $updated++;
            } else {
                $failed[] = "ID: {$id}";
            }
        }

        $message = "Đã check-in thành công {$updated} lịch hẹn.";
        if (count($failed) > 0) {
            $message .= " Không thể check-in: " . implode(', ', $failed);
        }

        return back()->with('success', $message);
    }
}

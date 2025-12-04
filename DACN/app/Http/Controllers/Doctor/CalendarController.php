<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\BacSi;
use App\Models\LichHen;
use App\Models\LichNghi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        $bacSi = BacSi::where('user_id', auth()->id())->first();
        if (!$bacSi) {
            return redirect()->route('dashboard')
                ->with('status', 'Tài khoản bác sĩ chưa được gắn với hồ sơ BacSi (user_id).');
        }

        return view('doctor.calendar.index', ['bacSi' => $bacSi]);
    }

    public function events(Request $request)
    {
        $bacSi = BacSi::where('user_id', auth()->id())->firstOrFail();

        $request->validate([
            'start' => ['required', 'date'],
            'end'   => ['required', 'date', 'after_or_equal:start'],
        ]);

        $start = Carbon::parse($request->start)->startOfDay();
        $end   = Carbon::parse($request->end)->endOfDay();

        $events = [];

        $appointments = LichHen::where('bac_si_id', $bacSi->id)
            ->whereBetween('ngay_hen', [$start->toDateString(), $end->toDateString()])
            ->with('dichVu')
            ->get(['id', 'ngay_hen', 'thoi_gian_hen', 'trang_thai', 'dich_vu_id']);

        foreach ($appointments as $a) {
            // thoi_gian_hen có thể lưu dạng "H:i" hoặc "H:i:s"; dùng parse cho linh hoạt
            $time = $a->thoi_gian_hen ?: '00:00';
            try {
                $s = Carbon::parse("{$a->ngay_hen} {$time}");
            } catch (\Exception $ex) {
                // Nếu không parse được, bỏ qua event thay vì gây lỗi 500
                continue;
            }

            $duration = optional($a->dichVu)->thoi_gian_uoc_tinh ?: 30;
            $e = (clone $s)->addMinutes($duration);
            $events[] = [
                'id' => "appt-{$a->id}",
                'title' => 'Lịch hẹn (' . ($a->trang_thai ?? 'N/A') . ')',
                'start' => $s->toIso8601String(),
                'end' => $e->toIso8601String(),
                'color' => '#0d6efd',
            ];
        }

        $leaves = LichNghi::where('bac_si_id', $bacSi->id)
            ->whereBetween('ngay', [$start->toDateString(), $end->toDateString()])
            ->get(['id', 'ngay', 'bat_dau', 'ket_thuc', 'ly_do']);

        foreach ($leaves as $l) {
            $bat_dau = $l->bat_dau ?: '00:00';
            $ket_thuc = $l->ket_thuc ?: '23:59';
            try {
                $s = Carbon::parse("{$l->ngay} {$bat_dau}");
                $e = Carbon::parse("{$l->ngay} {$ket_thuc}");
            } catch (\Exception $ex) {
                continue;
            }

            $events[] = [
                'id' => "leave-{$l->id}",
                'title' => 'Nghỉ' . ($l->ly_do ? ": {$l->ly_do}" : ''),
                'start' => $s->toIso8601String(),
                'end' => $e->toIso8601String(),
                'color' => '#adb5bd',
                'display' => 'background',
            ];
        }

        return response()->json($events);
    }
}

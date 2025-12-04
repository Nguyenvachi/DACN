<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LichHen;
use App\Models\LichNghi;
use App\Models\CaDieuChinhBacSi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CalendarApiController extends Controller
{
    public function index()
    {
        $doctors = \App\Models\BacSi::orderBy('id')->get(['id','ten','chuyen_khoa']);
        return view('admin.calendar.index', compact('doctors'));
    }

    public function events(Request $request)
    {
        try {
            $request->validate([
                'bac_si_id' => 'required|integer|min:1',
                'start'     => 'required|date',
                'end'       => 'required|date|after_or_equal:start',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([]);
        }

        $doctorId = (int)$request->query('bac_si_id');
        $start = Carbon::parse($request->query('start'))->startOfDay();
        $end   = Carbon::parse($request->query('end'))->endOfDay();

        $events = [];

        // Lịch hẹn: dùng ngay_hen + thoi_gian_hen nếu có
        if (Schema::hasTable('lich_hens') && Schema::hasColumn('lich_hens', 'ngay_hen') && Schema::hasColumn('lich_hens', 'thoi_gian_hen')) {
            $appts = LichHen::query()
                ->where('bac_si_id', $doctorId)
                ->whereBetween('ngay_hen', [$start->toDateString(), $end->toDateString()])
                ->get(['id','ngay_hen','thoi_gian_hen','trang_thai']);

            foreach ($appts as $a) {
                $s = Carbon::parse("{$a->ngay_hen} {$a->thoi_gian_hen}");
                $e = (clone $s)->addMinutes(30);
                $events[] = [
                    'id' => 'LH-'.$a->id,
                    'title' => 'Lịch hẹn'.($a->trang_thai ? ' ('.$a->trang_thai.')' : ''),
                    'start' => $s->toIso8601String(),
                    'end'   => $e->toIso8601String(),
                    'color' => '#0ea5e9',
                    'editable' => true,
                    'extendedProps' => [
                        'type' => 'lich_hen',
                        'bac_si_id' => $doctorId,
                        'raw_id' => $a->id,
                    ],
                ];
            }
        }

        // Lịch nghỉ: nếu có bat_dau/ket_thuc thì dùng giờ; nếu không → allDay
        if (Schema::hasTable('lich_nghis')) {
            $columns = Schema::getColumnListing('lich_nghis');
            $hasBD = in_array('bat_dau', $columns);
            $hasKT = in_array('ket_thuc', $columns);

            $offs = LichNghi::query()
                ->where('bac_si_id', $doctorId)
                ->whereBetween('ngay', [$start->toDateString(), $end->toDateString()])
                ->get(['id','ngay'] + ($hasBD ? ['bat_dau'] : []) + ($hasKT ? ['ket_thuc'] : []) + ['ly_do']);

            foreach ($offs as $o) {
                if ($hasBD && $hasKT) {
                    $s = Carbon::parse("{$o->ngay} {$o->bat_dau}");
                    $e = Carbon::parse("{$o->ngay} {$o->ket_thuc}");
                    $events[] = [
                        'id' => 'LN-'.$o->id,
                        'title' => 'Nghỉ'.($o->ly_do ? ': '.$o->ly_do : ''),
                        'start' => $s->toIso8601String(),
                        'end'   => $e->toIso8601String(),
                        'color' => '#ef4444',
                        'editable' => true,
                        'extendedProps' => [
                            'type' => 'lich_nghi',
                            'bac_si_id' => $doctorId,
                            'raw_id' => $o->id,
                        ],
                    ];
                } else {
                    $events[] = [
                        'id' => 'LN-'.$o->id,
                        'title' => 'Nghỉ cả ngày'.($o->ly_do ? ': '.$o->ly_do : ''),
                        'start' => Carbon::parse($o->ngay)->toIso8601String(),
                        'allDay' => true,
                        'color' => '#ef4444',
                        'editable' => false,
                        'extendedProps' => [
                            'type' => 'lich_nghi',
                            'bac_si_id' => $doctorId,
                            'raw_id' => $o->id,
                        ],
                    ];
                }
            }
        }

        // Ca điều chỉnh (nếu bảng tồn tại)
        if (Schema::hasTable('ca_dieu_chinh_bac_sis')) {
            $ovs = CaDieuChinhBacSi::query()
                ->where('bac_si_id', $doctorId)
                ->whereBetween('ngay', [$start->toDateString(), $end->toDateString()])
                ->get(['id','ngay','gio_bat_dau','gio_ket_thuc','hanh_dong']);

            foreach ($ovs as $ov) {
                if ($ov->gio_bat_dau && $ov->gio_ket_thuc) {
                    $s = Carbon::parse("{$ov->ngay} {$ov->gio_bat_dau}");
                    $e = Carbon::parse("{$ov->ngay} {$ov->gio_ket_thuc}");
                    $events[] = [
                        'id' => 'CDCB-'.$ov->id,
                        'title' => 'Điều chỉnh: '.$ov->hanh_dong,
                        'start' => $s->toIso8601String(),
                        'end'   => $e->toIso8601String(),
                        'color' => '#a855f7',
                        'editable' => true,
                        'extendedProps' => [
                            'type' => 'override',
                            'bac_si_id' => $doctorId,
                            'raw_id' => $ov->id,
                        ],
                    ];
                }
            }
        }

        return response()->json($events);
    }

    public function stats(Request $request)
    {
        try {
            $request->validate([
                'bac_si_id' => 'required|integer|min:1',
                'date'      => 'required|date',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['appointments'=>0,'working_hours'=>0,'overrides'=>0]);
        }

        $doctorId = (int)$request->query('bac_si_id');
        $date = Carbon::parse($request->query('date'))->toDateString();

        $appointments = 0;
        if (Schema::hasTable('lich_hens') && Schema::hasColumn('lich_hens', 'ngay_hen')) {
            $appointments = LichHen::query()
                ->where('bac_si_id', $doctorId)
                ->whereDate('ngay_hen', $date)
                ->count();
        }

        $overrides = 0;
        if (Schema::hasTable('ca_dieu_chinh_bac_sis')) {
            $overrides = CaDieuChinhBacSi::query()
                ->where('bac_si_id', $doctorId)
                ->whereDate('ngay', $date)
                ->count();
        }

        // Tính giờ làm việc trong ngày
        $workingMinutes = 0;
        $dayOfWeek = Carbon::parse($date)->dayOfWeek;

        if (Schema::hasTable('lich_lam_viecs')) {
            $shifts = \App\Models\LichLamViec::query()
                ->where('bac_si_id', $doctorId)
                ->where('ngay_trong_tuan', $dayOfWeek)
                ->get(['thoi_gian_bat_dau', 'thoi_gian_ket_thuc']);

            foreach ($shifts as $shift) {
                try {
                    $start = Carbon::parse($date . ' ' . ($shift->thoi_gian_bat_dau ?? '00:00'));
                    $end = Carbon::parse($date . ' ' . ($shift->thoi_gian_ket_thuc ?? '00:00'));
                    $workingMinutes += max(0, $end->diffInMinutes($start));
                } catch (\Throwable $e) {
                    // Skip invalid time
                }
            }
        }

        return response()->json([
            'appointments' => $appointments,
            'working_hours' => round($workingMinutes / 60, 2),
            'overrides' => $overrides,
        ]);
    }

    public function dragUpdate(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|string',
            'bac_si_id' => 'required|integer',
            'start' => 'required|date',
            'end' => 'required|date|after:start',
            'type' => 'required|string',
            'raw_id' => 'nullable|integer',
        ]);

        // Chỉ xử lý lịch hẹn (có cột ngay_hen/thoi_gian_hen)
        if ($validated['type'] === 'lich_hen' && Schema::hasTable('lich_hens') &&
            Schema::hasColumn('lich_hens', 'ngay_hen') && Schema::hasColumn('lich_hens', 'thoi_gian_hen')) {

            $rawId = $validated['raw_id'] ?? (preg_match('/LH-(\d+)/', $validated['id'], $m) ? (int)$m[1] : null);
            if (!$rawId) return response()->json(['ok'=>false,'message'=>'raw_id missing'], 422);

            $s = Carbon::parse($validated['start']);
            LichHen::query()->where('id', $rawId)->update([
                'ngay_hen' => $s->toDateString(),
                'thoi_gian_hen' => $s->format('H:i:s'),
            ]);
        }

        return response()->json(['ok'=>true]);
    }
}
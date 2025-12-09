<?php
/**
 * NOTE: checkConflictDoctor trong LichKhamService dùng các cột start_time/end_time không tồn tại.
 * Đã chuyển sang checkConflictDoctorV2. Hàm cũ coi như deprecated.
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LichHen;
use App\Models\LichLamViec;
use App\Models\LichNghi;
use App\Models\CaDieuChinhBacSi;
use App\Services\LichKhamService;
use App\Services\RoomAvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\BacSi;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CalendarController extends Controller
{
    protected $roomService;

    public function __construct(RoomAvailabilityService $roomService)
    {
        $this->roomService = $roomService;
    }
    // Bổ sung: trả về view với danh sách bác sĩ (UI)
    public function index()
    {
        // Select correct columns: BacSi model uses 'ho_ten' not 'ten'
        $doctors = \App\Models\BacSi::orderBy('id')->get(['id','ho_ten','chuyen_khoa']);
        return view('admin.calendar.index', compact('doctors'));
    }

    // Bổ sung: API events V2 (fallback, luôn trả JSON, tránh 500)
    public function apiEventsV2(\Illuminate\Http\Request $request)
    {
        try {
            $request->validate([
                'bac_si_id' => 'required|integer|min:1',
                'start'     => 'required|date',
                'end'       => 'required|date|after_or_equal:start',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([]); // thiếu param → trả rỗng
        }

        $doctorId = (int)$request->query('bac_si_id');
        $start    = \Carbon\Carbon::parse($request->query('start'))->startOfDay();
        $end      = \Carbon\Carbon::parse($request->query('end'))->endOfDay();

        $events = [];

        // Lịch hẹn (ngay_hen + thoi_gian_hen, mặc định 30’)
        $appts = collect();
        if (Schema::hasTable('lich_hens') && Schema::hasColumn('lich_hens','ngay_hen')) {
            $cols = ['id','ngay_hen'];
            if (Schema::hasColumn('lich_hens','thoi_gian_hen')) $cols[] = 'thoi_gian_hen';
            if (Schema::hasColumn('lich_hens','trang_thai')) $cols[] = 'trang_thai';
            $appts = \App\Models\LichHen::query()
                ->where('bac_si_id', $doctorId)
                ->whereBetween('ngay_hen', [$start->toDateString(), $end->toDateString()])
                ->get($cols);
        }

        foreach ($appts as $a) {
            try {
                // Bổ sung: làm sạch chuỗi datetime để tránh duplicate time spec
                $timeStr = trim($a->thoi_gian_hen ?? '00:00');
                // Nếu ngay_hen đã chứa cả date+time, chỉ dùng nó; nếu không thì nối
                $dateStr = trim($a->ngay_hen ?? '');
                if (strlen($dateStr) > 10) {
                    // Đã chứa cả date+time (dạng datetime), dùng nguyên
                    $s = \Carbon\Carbon::parse($dateStr);
                } else {
                    // Chỉ có date, nối thêm time
                    $s = \Carbon\Carbon::parse($dateStr . ' ' . $timeStr);
                }
            } catch (\Throwable $ex) {
                // Fallback an toàn khi parse lỗi
                try {
                    $s = \Carbon\Carbon::parse(trim($a->ngay_hen ?? date('Y-m-d')));
                } catch (\Throwable) {
                    $s = \Carbon\Carbon::now();
                }
            }
            $e = (clone $s)->addMinutes(\App\Services\LichKhamService::DEFAULT_APPT_MINUTES);
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

        // Ca làm việc (theo ngày trong tuần)
        $shifts = collect();
        if (Schema::hasTable('lich_lam_viecs') && Schema::hasColumn('lich_lam_viecs','ngay_trong_tuan')) {
            $cols = ['id','ngay_trong_tuan'];
            if (Schema::hasColumn('lich_lam_viecs','thoi_gian_bat_dau')) $cols[] = 'thoi_gian_bat_dau';
            if (Schema::hasColumn('lich_lam_viecs','thoi_gian_ket_thuc')) $cols[] = 'thoi_gian_ket_thuc';
            $shifts = \App\Models\LichLamViec::query()
                ->where('bac_si_id', $doctorId)
                ->get($cols);
        }

        $cursor = $start->copy();
        while ($cursor->lte($end)) {
            $dow = $cursor->dayOfWeek;
            foreach ($shifts as $sft) {
                if ((int)$sft->ngay_trong_tuan === $dow) {
                    // Bổ sung: trim để tránh lỗi parse
                    try {
                        $s = \Carbon\Carbon::parse($cursor->toDateString().' '.trim($sft->thoi_gian_bat_dau ?? '00:00'));
                        $e = \Carbon\Carbon::parse($cursor->toDateString().' '.trim($sft->thoi_gian_ket_thuc ?? '23:59'));
                    } catch (\Throwable $ex) {
                        continue; // Bỏ qua shift bị lỗi parse
                    }
                    $events[] = [
                        'id' => 'LLV-'.$sft->id.'-'.$cursor->toDateString(),
                        'title' => 'Ca làm việc',
                        'start' => $s->toIso8601String(),
                        'end'   => $e->toIso8601String(),
                        'color' => '#22c55e',
                        'editable' => true,
                        'extendedProps' => [
                            'type' => 'lich_lam_viec',
                            'bac_si_id' => $doctorId,
                            'raw_id' => $sft->id,
                            'date' => $cursor->toDateString(),
                        ],
                    ];
                }
            }
            $cursor->addDay();
        }

        // Lịch nghỉ
        $offs = collect();
        if (Schema::hasTable('lich_nghis') && Schema::hasColumn('lich_nghis','ngay')) {
            $cols = ['id','ngay'];
            if (Schema::hasColumn('lich_nghis','bat_dau')) $cols[] = 'bat_dau';
            if (Schema::hasColumn('lich_nghis','ket_thuc')) $cols[] = 'ket_thuc';
            if (Schema::hasColumn('lich_nghis','ly_do')) $cols[] = 'ly_do';
            $offs = \App\Models\LichNghi::query()
                ->where('bac_si_id', $doctorId)
                ->whereBetween('ngay', [$start->toDateString(), $end->toDateString()])
                ->get($cols);
        }

        foreach ($offs as $o) {
            // Bổ sung: try/catch và trim
            try {
                $s = \Carbon\Carbon::parse(trim($o->ngay).' '.trim($o->bat_dau ?: '00:00'));
                $e = \Carbon\Carbon::parse(trim($o->ngay).' '.trim($o->ket_thuc ?: '23:59'));
            } catch (\Throwable $ex) {
                continue; // Bỏ qua offs bị lỗi parse
            }
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
        }

        // Ca điều chỉnh
        $overrides = collect();
        if (Schema::hasTable('ca_dieu_chinh_bac_sis') && Schema::hasColumn('ca_dieu_chinh_bac_sis','ngay')) {
            $cols = ['id','ngay'];
            foreach (['gio_bat_dau','gio_ket_thuc','hanh_dong','ly_do'] as $c) {
                if (Schema::hasColumn('ca_dieu_chinh_bac_sis',$c)) $cols[] = $c;
            }
            $overrides = \App\Models\CaDieuChinhBacSi::query()
                ->where('bac_si_id', $doctorId)
                ->whereBetween('ngay', [$start->toDateString(), $end->toDateString()])
                ->get($cols);
        }

        foreach ($overrides as $ov) {
            // Bổ sung: try/catch và trim
            try {
                $s = \Carbon\Carbon::parse(trim($ov->ngay).' '.trim($ov->gio_bat_dau ?: '00:00'));
                $e = \Carbon\Carbon::parse(trim($ov->ngay).' '.trim($ov->gio_ket_thuc ?: '23:59'));
            } catch (\Throwable $ex) {
                continue; // Bỏ qua override bị lỗi parse
            }
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

        return response()->json($events);
    }

    /**
     * API trả về tất cả events cho FullCalendar
     */
    public function apiEvents(Request $request)
    {
        // Bổ sung guard an toàn (chỉ thêm code, không xóa):
        // Nếu thiếu tham số → trả về mảng rỗng để tránh 500.
        try {
            $request->validate([
                'bac_si_id' => 'required|integer|exists:bac_sis,id',
                'start'     => 'required|date',
                'end'       => 'required|date|after_or_equal:start',
            ]);
        } catch (ValidationException $e) {
            return response()->json([], 200);
        }

        $doctorId = (int) $request->bac_si_id;
        $start    = Carbon::parse($request->start)->startOfDay();
        $end      = Carbon::parse($request->end)->endOfDay();

        $events = [];

        /* ---------------------------------------------------
         | 1) LỊCH HẸN
         --------------------------------------------------- */
        $appointments = collect();
        if (Schema::hasTable('lich_hens') && Schema::hasColumn('lich_hens','ngay_hen')) {
            try {
                $appointments = LichHen::where('bac_si_id', $doctorId)
                    ->whereBetween('ngay_hen', [$start->toDateString(), $end->toDateString()])
                    ->get(['id', 'ngay_hen', 'thoi_gian_hen', 'trang_thai']);
            } catch (\Throwable $ex) {
                \Illuminate\Support\Facades\Log::warning('apiEvents: query appointments failed: '.$ex->getMessage());
            }
        }

        foreach ($appointments as $a) {
            try {
                $dateOnly = $a->ngay_hen instanceof \Carbon\Carbon ? $a->ngay_hen->format('Y-m-d') : (string)$a->ngay_hen;
                $s = \Carbon\Carbon::parse($dateOnly . ' ' . ($a->thoi_gian_hen ?: '00:00'));
            } catch (\Throwable) {
                $s = \Carbon\Carbon::parse((string)$a->ngay_hen . ' 00:00');
            }
            $e = (clone $s)->addMinutes(\App\Services\LichKhamService::DEFAULT_APPT_MINUTES);

            $events[] = [
                'id'    => "LH-{$a->id}-{$s->timestamp}",
                'title' => 'Lịch hẹn (' . ($a->trang_thai ?? '') . ')',
                'start' => $s->toIso8601String(),
                'end'   => $e->toIso8601String(),
                'color' => '#0ea5e9',
                'editable' => true,
                'extendedProps' => [
                    'type'       => 'lich_hen',
                    'raw_id'     => $a->id,
                    'bac_si_id'  => $doctorId,
                ],
            ];
        }

        /* ---------------------------------------------------
         | 2) LỊCH LÀM VIỆC (sinh theo tuần)
         --------------------------------------------------- */
        $shifts = collect();
        if (Schema::hasTable('lich_lam_viecs') && Schema::hasColumn('lich_lam_viecs','ngay_trong_tuan')) {
            try {
                $shifts = LichLamViec::where('bac_si_id', $doctorId)
                    ->get(['id', 'ngay_trong_tuan', 'thoi_gian_bat_dau', 'thoi_gian_ket_thuc']);
            } catch (\Throwable $ex) {
                \Illuminate\Support\Facades\Log::warning('apiEvents: query shifts failed: '.$ex->getMessage());
            }
        }

        // group theo dayOfWeek
        $shiftsByDow = [];
        foreach ($shifts as $sft) {
            $dow = (int) $sft->ngay_trong_tuan;
            $shiftsByDow[$dow][] = $sft;
        }

        $period = $start->copy();
        while ($period->lte($end)) {
            $dow = $period->dayOfWeek;

            if (!empty($shiftsByDow[$dow])) {
                foreach ($shiftsByDow[$dow] as $sft) {

                    $date = $period->toDateString();

                    // parse giờ an toàn
                    $s = Carbon::parse($date . ' ' . ($sft->thoi_gian_bat_dau ?: '00:00'));
                    $e = Carbon::parse($date . ' ' . ($sft->thoi_gian_ket_thuc ?: '23:59'));

                    $events[] = [
                        'id'    => "LLV-{$sft->id}-{$s->timestamp}",
                        'title' => 'Ca làm việc',
                        'start' => $s->toIso8601String(),
                        'end'   => $e->toIso8601String(),
                        'color' => '#22c55e',
                        'editable' => true,
                        'extendedProps' => [
                            'type'      => 'lich_lam_viec',
                            'raw_id'    => $sft->id,
                            'bac_si_id' => $doctorId,
                            'date'      => $date,
                        ],
                    ];
                }
            }
            $period->addDay();
        }

        /* ---------------------------------------------------
         | 3) LỊCH NGHỈ
         --------------------------------------------------- */
        $offs = collect();
        if (Schema::hasTable('lich_nghis') && Schema::hasColumn('lich_nghis','ngay')) {
            try {
                $offs = LichNghi::where('bac_si_id', $doctorId)
                    ->whereBetween('ngay', [$start->toDateString(), $end->toDateString()])
                    ->get(['id', 'ngay', 'bat_dau', 'ket_thuc', 'ly_do']);
            } catch (\Throwable $ex) {
                \Illuminate\Support\Facades\Log::warning('apiEvents: query offs failed: '.$ex->getMessage());
            }
        }

        foreach ($offs as $o) {
            $s = Carbon::parse($o->ngay . ' ' . ($o->bat_dau ?: '00:00'));
            $e = Carbon::parse($o->ngay . ' ' . ($o->ket_thuc ?: '23:59'));

            $events[] = [
                'id'    => "LN-{$o->id}-{$s->timestamp}",
                'title' => 'Nghỉ: ' . ($o->ly_do ?? ''),
                'start' => $s->toIso8601String(),
                'end'   => $e->toIso8601String(),
                'color' => '#ef4444',
                'editable' => true,
                'extendedProps' => [
                    'type'      => 'lich_nghi',
                    'raw_id'    => $o->id,
                    'bac_si_id' => $doctorId,
                ],
            ];
        }

        /* ---------------------------------------------------
         | 4) CA ĐIỀU CHỈNH (nghỉ đột xuất / tăng ca)
         --------------------------------------------------- */
        $overrides = collect();
        try {
            $overrides = CaDieuChinhBacSi::where('bac_si_id', $doctorId)
                ->whereBetween('ngay', [$start->toDateString(), $end->toDateString()])
                ->get(['id', 'ngay', 'gio_bat_dau', 'gio_ket_thuc', 'hanh_dong', 'ly_do']);
        } catch (\Throwable $ex) {
            \Illuminate\Support\Facades\Log::warning('apiEvents: query overrides failed: '.$ex->getMessage());
        }

        foreach ($overrides as $ov) {
            $s = Carbon::parse($ov->ngay . ' ' . ($ov->gio_bat_dau ?: '00:00'));
            $e = Carbon::parse($ov->ngay . ' ' . ($ov->gio_ket_thuc ?: '23:59'));

            $events[] = [
                'id'    => "CDCB-{$ov->id}-{$s->timestamp}",
                'title' => 'Điều chỉnh: ' . ($ov->hanh_dong ?? $ov->ly_do ?? ''),
                'start' => $s->toIso8601String(),
                'end'   => $e->toIso8601String(),
                'color' => '#a855f7',
                'editable' => true,
                'extendedProps' => [
                    'type'      => 'override',
                    'raw_id'    => $ov->id,
                    'bac_si_id' => $doctorId,
                ],
            ];
        }

        return response()->json($events);
    }

    /**
     * API Drag/Drop hoặc Resize cập nhật thời gian
     */
    public function apiDragUpdate(Request $request, LichKhamService $service)
    {
        $validated = $request->validate([
            'id'        => 'required|string',
            'bac_si_id' => 'required|integer|exists:bac_sis,id',
            'start'     => 'required|date',
            'end'       => 'required|date|after:start',
            'type'      => 'required|string',
        ]);

        $start = Carbon::parse($validated['start']);
        $end   = Carbon::parse($validated['end']);
        $type  = $validated['type'];
        $bacSiId = (int) $validated['bac_si_id'];

        // lấy số ID thật từ chuỗi LH-123-...
        $rawId = $request->input('raw_id');
        if (!$rawId) {
            // fallback: lấy CHUỖI ngay sau tiền tố LH-/LLV-/LN-/CDCB-
            if (preg_match('/^(LH|LLV|LN|CDCB)-(\d+)/', $validated['id'], $m)) {
                $rawId = $m[2];
            } elseif (preg_match('/(\d+)/', $validated['id'], $m)) {
                $rawId = $m[1];
            }
        }
        if (!$rawId) {
            return response()->json(['ok' => false, 'message' => 'Cannot resolve raw_id'], 422);
        }

        // Kiểm tra conflict
        $ignoreApptId = ($type === 'lich_hen') ? $rawId : null;
        $conf = $service->checkConflictDoctorV2($bacSiId, $start, $end, $ignoreApptId);

        if (!empty($conf['conflict'])) {
            return response()->json(['ok' => false, 'conflicts' => $conf['details']], 409);
        }

        // Update theo type
        switch ($type) {
            case 'lich_hen':
                LichHen::where('id', $rawId)->update([
                    'ngay_hen'       => $start->toDateString(),
                    'thoi_gian_hen'  => $start->format('H:i:s'),
                ]);
                break;

            case 'lich_lam_viec':
                LichLamViec::where('id', $rawId)->update([
                    'ngay_trong_tuan'    => $start->dayOfWeek,
                    'thoi_gian_bat_dau'  => $start->format('H:i:s'),
                    'thoi_gian_ket_thuc' => $end->format('H:i:s'),
                ]);
                break;

            case 'lich_nghi':
                LichNghi::where('id', $rawId)->update([
                    'ngay'       => $start->toDateString(),
                    'bat_dau'    => $start->format('H:i:s'),
                    'ket_thuc'   => $end->format('H:i:s'),
                ]);
                break;

            case 'override':
                CaDieuChinhBacSi::where('id', $rawId)->update([
                    'ngay'         => $start->toDateString(),
                    'gio_bat_dau'  => $start->format('H:i:s'),
                    'gio_ket_thuc' => $end->format('H:i:s'),
                ]);
                break;

            default:
                return response()->json(['ok' => false, 'message' => 'Unknown event type'], 422);
        }

        return response()->json(['ok' => true]);
    }

    /**
     * API thống kê tải lịch
     */
    public function apiStats(Request $request)
    {
        $doctorId = (int) $request->query('bac_si_id', 0);
        $date     = $request->query('date') ?? Carbon::now()->toDateString();

        if ($doctorId <= 0) {
            return response()->json([
                'appointments'  => 0,
                'working_hours' => 0,
                'overrides'     => 0,
            ]);
        }

        $appointments = LichHen::where('bac_si_id', $doctorId)
            ->whereDate('ngay_hen', $date)
            ->count();

        // tính tổng giờ làm
        $dow = Carbon::parse($date)->dayOfWeek;

        $shifts = LichLamViec::where('bac_si_id', $doctorId)
            ->where('ngay_trong_tuan', $dow)
            ->get(['thoi_gian_bat_dau', 'thoi_gian_ket_thuc']);

        $workingMinutes = 0;

        foreach ($shifts as $sft) {
            try {
                $s = Carbon::parse($date . ' ' . ($sft->thoi_gian_bat_dau ?: '00:00'));
                $e = Carbon::parse($date . ' ' . ($sft->thoi_gian_ket_thuc ?: '00:00'));
                $workingMinutes += max(0, $e->diffInMinutes($s));
            } catch (\Throwable $ex) {
                Log::warning("Invalid shift for bác sĩ {$doctorId} ngày {$date}: " . $ex->getMessage());
            }
        }

        // điều chỉnh
        $overrides = CaDieuChinhBacSi::where('bac_si_id', $doctorId)
            ->whereDate('ngay', $date)
            ->count();

        return response()->json([
            'appointments'  => $appointments,
            'working_hours' => round($workingMinutes / 60, 2),
            'overrides'     => $overrides,
        ]);
    }

    // Bổ sung: API stats V2 (fallback, tránh 500)
    public function apiStatsV2(\Illuminate\Http\Request $request)
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
        $date = \Carbon\Carbon::parse($request->query('date'))->toDateString();

        $appointments = \App\Models\LichHen::query()
            ->where('bac_si_id', $doctorId)
            ->whereDate('ngay_hen', $date)
            ->count();

        $dow = \Carbon\Carbon::parse($date)->dayOfWeek;
        $shifts = \App\Models\LichLamViec::query()
            ->where('bac_si_id', $doctorId)
            ->where('ngay_trong_tuan', $dow)
            ->get(['thoi_gian_bat_dau','thoi_gian_ket_thuc']);

        $workingMinutes = 0;
        foreach ($shifts as $sft) {
            try {
                $startTime = $sft['thoi_gian_bat_dau'] ?? $sft->thoi_gian_bat_dau ?? '00:00';
                $endTime = $sft['thoi_gian_ket_thuc'] ?? $sft->thoi_gian_ket_thuc ?? '00:00';
                $s = \Carbon\Carbon::parse($date.' '.$startTime);
                $e = \Carbon\Carbon::parse($date.' '.$endTime);
                $workingMinutes += max(0, $e->diffInMinutes($s));
            } catch (\Throwable $ex) {
                \Illuminate\Support\Facades\Log::warning("Invalid shift time in apiStatsV2: " . $ex->getMessage());
            }
        }

        $overrides = \App\Models\CaDieuChinhBacSi::query()
            ->where('bac_si_id', $doctorId)
            ->whereDate('ngay', $date)
            ->count();

        return response()->json([
            'appointments' => $appointments,
            'working_hours' => round($workingMinutes / 60, 2),
            'overrides' => $overrides,
        ]);
    }
}

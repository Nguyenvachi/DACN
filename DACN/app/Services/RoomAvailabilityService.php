<?php

namespace App\Services;

use App\Models\Phong;
use App\Models\LichLamViec;
use App\Models\LichHen;
use App\Models\CaDieuChinhBacSi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

/**
 * Service kiểm tra khả dụng phòng và phát hiện xung đột
 * Parent file: app/Services/RoomAvailabilityService.php
 */
class RoomAvailabilityService
{
    /**
     * Kiểm tra xung đột phòng trong khoảng thời gian
     *
     * @param int $phongId
     * @param Carbon $start
     * @param Carbon $end
     * @param int|null $ignoreLichHenId - Bỏ qua lịch hẹn này (khi update)
     * @param int|null $ignoreLichLamViecId - Bỏ qua lịch làm việc này (khi update)
     * @param int|null $ignoreBacSiId - Bỏ qua bác sĩ này (đang đặt lịch)
     * @return array ['conflict' => bool, 'details' => array]
     */
    public function checkRoomConflict(
        int $phongId,
        Carbon $start,
        Carbon $end,
        ?int $ignoreLichHenId = null,
        ?int $ignoreLichLamViecId = null,
        ?int $ignoreBacSiId = null
    ): array {
        $conflicts = [];

        // 1. Kiểm tra lịch làm việc đang sử dụng phòng này (loại trừ bác sĩ đang đặt)
        $dayOfWeek = $start->dayOfWeek;
        $conflictShifts = LichLamViec::where('phong_id', $phongId)
            ->where('ngay_trong_tuan', $dayOfWeek)
            ->when($ignoreLichLamViecId, function ($q) use ($ignoreLichLamViecId) {
                $q->where('id', '!=', $ignoreLichLamViecId);
            })
            ->when($ignoreBacSiId, function ($q) use ($ignoreBacSiId) {
                $q->where('bac_si_id', '!=', $ignoreBacSiId);
            })
            ->get();

        foreach ($conflictShifts as $shift) {
            $shiftStart = Carbon::parse($start->toDateString() . ' ' . $shift->thoi_gian_bat_dau);
            $shiftEnd = Carbon::parse($start->toDateString() . ' ' . $shift->thoi_gian_ket_thuc);

            if ($this->timeOverlaps($start, $end, $shiftStart, $shiftEnd)) {
                $conflicts[] = sprintf(
                    'Phòng đã được sử dụng bởi %s vào %s - %s',
                    $shift->bacSi->ho_ten ?? 'bác sĩ khác',
                    $shift->thoi_gian_bat_dau,
                    $shift->thoi_gian_ket_thuc
                );
            }
        }        // 2. Kiểm tra lịch hẹn đã đặt trong phòng này (thông qua bác sĩ)
        $dateString = $start->toDateString();
        $conflictAppointments = LichHen::whereDate('ngay_hen', $dateString)
            ->whereHas('bacSi.lichLamViecs', function ($q) use ($phongId, $dayOfWeek) {
                $q->where('phong_id', $phongId)
                  ->where('ngay_trong_tuan', $dayOfWeek);
            })
            ->when($ignoreLichHenId, function ($q) use ($ignoreLichHenId) {
                $q->where('id', '!=', $ignoreLichHenId);
            })
            ->get();

        foreach ($conflictAppointments as $appt) {
            // Chỉ lấy date part để tránh double time
            $dateOnly = Carbon::parse($appt->ngay_hen)->toDateString();
            $apptStart = Carbon::parse($dateOnly . ' ' . $appt->thoi_gian_hen);
            $duration = $appt->dichVu->thoi_gian_uoc_tinh ?? 30;
            $apptEnd = $apptStart->copy()->addMinutes($duration);

            if ($this->timeOverlaps($start, $end, $apptStart, $apptEnd)) {
                $conflicts[] = sprintf(
                    'Lịch hẹn #%d (%s) đã sử dụng phòng lúc %s',
                    $appt->id,
                    $appt->bacSi->ho_ten ?? 'N/A',
                    $appt->thoi_gian_hen
                );
            }
        }        // 3. Kiểm tra ca điều chỉnh
        $conflictAdjustments = CaDieuChinhBacSi::whereDate('ngay', $dateString)
            ->whereHas('bacSi.lichLamViecs', function ($q) use ($phongId, $dayOfWeek) {
                $q->where('phong_id', $phongId)
                  ->where('ngay_trong_tuan', $dayOfWeek);
            })
            ->get();

        foreach ($conflictAdjustments as $adj) {
            $dateOnly = Carbon::parse($adj->ngay)->toDateString();
            $adjStart = Carbon::parse($dateOnly . ' ' . $adj->gio_bat_dau);
            $adjEnd = Carbon::parse($dateOnly . ' ' . $adj->gio_ket_thuc);

            if ($this->timeOverlaps($start, $end, $adjStart, $adjEnd)) {
                $conflicts[] = sprintf(
                    'Ca điều chỉnh (%s) từ %s - %s',
                    $adj->hanh_dong,
                    $adj->gio_bat_dau,
                    $adj->gio_ket_thuc
                );
            }
        }        return [
            'conflict' => !empty($conflicts),
            'details' => $conflicts
        ];
    }

    /**
     * Kiểm tra 2 khoảng thời gian có overlap không
     */
    private function timeOverlaps(Carbon $start1, Carbon $end1, Carbon $start2, Carbon $end2): bool
    {
        return $start1->lt($end2) && $end1->gt($start2);
    }

    /**
     * Lấy danh sách phòng khả dụng trong khoảng thời gian
     *
     * @param Carbon $start
     * @param Carbon $end
     * @return \Illuminate\Support\Collection
     */
    public function getAvailableRooms(Carbon $start, Carbon $end)
    {
        $allRooms = Phong::where('trang_thai', 'Sẵn sàng')->get();

        return $allRooms->filter(function ($room) use ($start, $end) {
            $check = $this->checkRoomConflict($room->id, $start, $end);
            return !$check['conflict'];
        });
    }

    /**
     * Lấy trạng thái phòng tại thời điểm cụ thể
     * Trả về: 'available', 'occupied', 'maintenance'
     *
     * @param int $phongId
     * @param Carbon $datetime
     * @return string
     */
    public function getRoomStatus(int $phongId, Carbon $datetime): string
    {
        $phong = Phong::find($phongId);

        if (!$phong) {
            return 'unknown';
        }

        // Kiểm tra trạng thái cơ bản
        if ($phong->trang_thai === 'Bảo trì') {
            return 'maintenance';
        }

        // Kiểm tra có đang được sử dụng không
        $end = $datetime->copy()->addMinutes(1);
        $check = $this->checkRoomConflict($phongId, $datetime, $end);

        return $check['conflict'] ? 'occupied' : 'available';
    }

    /**
     * Lấy thống kê sử dụng phòng theo ngày
     * Cache 5 phút
     *
     * @param int $phongId
     * @param string $date (Y-m-d)
     * @return array
     */
    public function getRoomUsageStats(int $phongId, string $date): array
    {
        $cacheKey = "room_usage_{$phongId}_{$date}";

        return Cache::remember($cacheKey, 300, function () use ($phongId, $date) {
            $dayOfWeek = Carbon::parse($date)->dayOfWeek;

            // Tổng giờ làm việc được phân bổ
            $shifts = LichLamViec::where('phong_id', $phongId)
                ->where('ngay_trong_tuan', $dayOfWeek)
                ->get();

            $totalMinutes = 0;
            foreach ($shifts as $shift) {
                $start = Carbon::parse($shift->thoi_gian_bat_dau);
                $end = Carbon::parse($shift->thoi_gian_ket_thuc);
                $totalMinutes += $start->diffInMinutes($end);
            }

            // Số lịch hẹn đã đặt
            $appointments = LichHen::whereDate('ngay_hen', $date)
                ->whereHas('bacSi.lichLamViecs', function ($q) use ($phongId, $dayOfWeek) {
                    $q->where('phong_id', $phongId)
                      ->where('ngay_trong_tuan', $dayOfWeek);
                })
                ->count();

            // Số bác sĩ sử dụng phòng
            $doctors = LichLamViec::where('phong_id', $phongId)
                ->where('ngay_trong_tuan', $dayOfWeek)
                ->distinct('bac_si_id')
                ->count('bac_si_id');

            return [
                'phong_id' => $phongId,
                'date' => $date,
                'total_hours' => round($totalMinutes / 60, 2),
                'appointments' => $appointments,
                'doctors' => $doctors,
                'utilization' => $totalMinutes > 0 ? round(($appointments * 30) / $totalMinutes * 100, 2) : 0
            ];
        });
    }

    /**
     * Lấy lịch sử sử dụng phòng trong khoảng thời gian
     *
     * @param int $phongId
     * @param Carbon $from
     * @param Carbon $to
     * @return \Illuminate\Support\Collection
     */
    public function getRoomHistory(int $phongId, Carbon $from, Carbon $to)
    {
        return LichHen::whereBetween('ngay_hen', [$from->toDateString(), $to->toDateString()])
            ->whereHas('bacSi.lichLamViecs', function ($q) use ($phongId) {
                $q->where('phong_id', $phongId);
            })
            ->with(['bacSi', 'dichVu', 'user'])
            ->orderBy('ngay_hen')
            ->orderBy('thoi_gian_hen')
            ->get();
    }

    /**
     * Tự động gợi ý phòng trống cho bác sĩ vào thời điểm
     *
     * @param int $bacSiId
     * @param Carbon $start
     * @param Carbon $end
     * @return \Illuminate\Support\Collection
     */
    public function suggestRoomsForDoctor(int $bacSiId, Carbon $start, Carbon $end)
    {
        // Lấy phòng bác sĩ thường sử dụng
        $preferredRooms = LichLamViec::where('bac_si_id', $bacSiId)
            ->whereNotNull('phong_id')
            ->distinct()
            ->pluck('phong_id');

        $allRooms = Phong::where('trang_thai', 'Sẵn sàng')
            ->get()
            ->sortBy(function ($room) use ($preferredRooms) {
                return $preferredRooms->contains($room->id) ? 0 : 1;
            });

        return $allRooms->filter(function ($room) use ($start, $end) {
            $check = $this->checkRoomConflict($room->id, $start, $end);
            return !$check['conflict'];
        })->values();
    }
}

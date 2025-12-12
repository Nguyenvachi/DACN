<?php

namespace App\Services;

use App\Models\LichHen;
use App\Models\LichNghi;
use App\Models\CaDieuChinhBacSi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LichKhamService
{
    // Trả về ['conflict' => bool, 'details' => array]
    // TODO: map đúng tên cột thời gian theo DB thực tế.
    public function checkConflictDoctor(int $bacSiId, Carbon $start, Carbon $end, ?int $ignoreLichHenId = null): array
    {
        $conflicts = [];

        // Lịch nghỉ
        try {
            $off = DB::table('lich_nghis')
                ->where('bac_si_id', $bacSiId)
                ->where(function ($q) use ($start, $end) {
                    $q->whereBetween('start_time', [$start, $end])
                        ->orWhereBetween('end_time', [$start, $end])
                        ->orWhere(function ($q2) use ($start, $end) {
                            $q2->where('start_time', '<=', $start)
                                ->where('end_time', '>=', $end);
                        });
                })
                ->exists();
            if ($off) $conflicts[] = 'Trùng với lịch nghỉ';
        } catch (\Throwable) {
        }

        // Lịch hẹn
        try {
            $appointments = DB::table('lich_hens')
                ->where('bac_si_id', $bacSiId)
                ->when($ignoreLichHenId, fn($q) => $q->where('id', '!=', $ignoreLichHenId))
                ->where(function ($q) use ($start, $end) {
                    $q->whereBetween('start_time', [$start, $end])
                        ->orWhereBetween('end_time', [$start, $end])
                        ->orWhere(function ($q2) use ($start, $end) {
                            $q2->where('start_time', '<=', $start)
                                ->where('end_time', '>=', $end);
                        });
                })
                ->exists();
            if ($appointments) $conflicts[] = 'Trùng với lịch hẹn khác';
        } catch (\Throwable) {
        }

        // Ca điều chỉnh
        try {
            $overrides = DB::table('ca_dieu_chinh_bac_sis')
                ->where('bac_si_id', $bacSiId)
                ->whereDate('ngay', $start->toDateString())
                ->whereRaw('? < TIME(gio_ket_thuc) AND ? > TIME(gio_bat_dau)', [
                    $start->format('H:i:s'),
                    $end->format('H:i:s'),
                ])
                ->exists();
            if ($overrides) $conflicts[] = 'Trùng với ca điều chỉnh';
        } catch (\Throwable) {
        }

        return ['conflict' => !empty($conflicts), 'details' => $conflicts];
    }

    public const DEFAULT_APPT_MINUTES = 30;

    protected function overlap(string $s1, string $e1, string $s2, string $e2): bool
    {
        // So sánh theo chuỗi HH:MM:SS là an toàn vì định dạng thời gian cố định
        return ($s1 < $e2) && ($e1 > $s2);
    }

    /**
     * Kiểm tra xung đột theo cột thực tế trong DB.
     * - LichHen: ngay_hen + thoi_gian_hen (mặc định +30 phút)
     * - LichNghi: ngay + [bat_dau, ket_thuc]
     * - CaDieuChinhBacSi: ngay + [gio_bat_dau, gio_ket_thuc]
     * - LichLamViec: kiểm tra xem có lịch làm việc trong ngày đó không
     */
    public function checkConflictDoctorV2(int $bacSiId, Carbon $start, Carbon $end, ?int $ignoreLichHenId = null): array
    {
        $conflicts = [];
        $date = $start->toDateString();
        $startTime = $start->format('H:i:s');
        $endTime = $end->format('H:i:s');
        $dayOfWeek = $start->dayOfWeek;

        // 0) Kiểm tra xem bác sĩ có lịch làm việc trong ngày này không
        $hasWorkingSchedule = \App\Models\LichLamViec::query()
            ->where('bac_si_id', $bacSiId)
            ->where('ngay_trong_tuan', $dayOfWeek)
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where(function ($q2) use ($startTime, $endTime) {
                    $q2->where('thoi_gian_bat_dau', '<=', $startTime)
                        ->where('thoi_gian_ket_thuc', '>=', $endTime);
                })
                    ->orWhere(function ($q2) use ($startTime, $endTime) {
                        // Hoặc có overlap
                        $q2->where('thoi_gian_bat_dau', '<', $endTime)
                            ->where('thoi_gian_ket_thuc', '>', $startTime);
                    });
            })
            ->exists();

        // Kiểm tra ca điều chỉnh loại "add" trong ngày (có thể bổ sung giờ làm)
        $hasOverrideAdd = CaDieuChinhBacSi::query()
            ->where('bac_si_id', $bacSiId)
            ->whereDate('ngay', $date)
            ->where('hanh_dong', 'add')
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where('gio_bat_dau', '<=', $startTime)
                    ->where('gio_ket_thuc', '>=', $endTime);
            })
            ->orWhere(function ($q) use ($startTime, $endTime) {
                $q->where('gio_bat_dau', '<', $endTime)
                    ->where('gio_ket_thuc', '>', $startTime);
            })
            ->exists();

        if (!$hasWorkingSchedule && !$hasOverrideAdd) {
            $conflicts[] = 'Bác sĩ không có lịch làm việc trong khung giờ này';
        }

        // 1) Lịch nghỉ trong ngày
        $lichNghi = LichNghi::query()
            ->where('bac_si_id', $bacSiId)
            ->whereDate('ngay', $date)
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where(function ($q2) use ($startTime, $endTime) {
                    $q2->where('bat_dau', '<', $endTime)
                        ->where('ket_thuc', '>', $startTime);
                });
            })
            ->exists();

        if ($lichNghi) {
            $conflicts[] = 'Trùng với lịch nghỉ';
        }

        // 2) Lịch hẹn khác trong cùng ngày - CHO PHÉP TỐI ĐA 2 NGƯỜI CÙNG KHUNG GIỜ
        // Đếm số lượng lịch hẹn cùng thời gian (không tính lịch đang ignore)
        $sameTimeCount = LichHen::query()
            ->where('bac_si_id', $bacSiId)
            ->when($ignoreLichHenId, fn($q) => $q->where('id', '!=', $ignoreLichHenId))
            ->whereDate('ngay_hen', $date)
            ->where('thoi_gian_hen', $startTime)
            ->whereNotIn('trang_thai', [LichHen::STATUS_CANCELLED_VN])
            ->count();

        if ($sameTimeCount >= 2) {
            $conflicts[] = 'Khung giờ này đã đủ 2 người đặt';
        }

        // 3) Ca điều chỉnh đơn lẻ cùng ngày
        $override = CaDieuChinhBacSi::query()
            ->where('bac_si_id', $bacSiId)
            ->whereDate('ngay', $date)
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where('gio_bat_dau', '<', $endTime)
                    ->where('gio_ket_thuc', '>', $startTime);
            })
            ->exists();

        if ($override) {
            $conflicts[] = 'Trùng với ca điều chỉnh';
        }

        return [
            'conflict' => !empty($conflicts),
            'details' => $conflicts,
        ];
    }

    /**
     * Kiểm tra xung đột cho bác sĩ theo ngày/giờ (dựa trên cột thực tế) với duration phút.
     */
    public function hasConflictForDoctor(int $bacSiId, string $ngay_hen, string $thoi_gian_hen, int $durationMinutes = self::DEFAULT_APPT_MINUTES, ?int $ignoreLichHenId = null): array
    {
        $dateOnly = Carbon::parse($ngay_hen)->format('Y-m-d');
        $start = Carbon::parse($dateOnly . ' ' . $thoi_gian_hen);
        $end = $start->copy()->addMinutes($durationMinutes);
        return $this->checkConflictDoctorV2($bacSiId, $start, $end, $ignoreLichHenId);
    }

    /**
     * Kiểm tra bệnh nhân có lịch khác trùng thời gian hay không (cùng ngày) với duration phút.
     */
    public function hasPatientConflict(int $userId, string $ngay_hen, string $thoi_gian_hen, int $durationMinutes = self::DEFAULT_APPT_MINUTES, ?int $ignoreLichHenId = null): array
    {
        $dateOnly = Carbon::parse($ngay_hen)->format('Y-m-d');
        $start = Carbon::parse($dateOnly . ' ' . $thoi_gian_hen);
        $end = $start->copy()->addMinutes($durationMinutes);

        $rows = \App\Models\LichHen::query()
            ->where('user_id', $userId)
            ->when($ignoreLichHenId, fn($q) => $q->where('id', '!=', $ignoreLichHenId))
            ->whereDate('ngay_hen', $ngay_hen)
            ->whereNotIn('trang_thai', [\App\Models\LichHen::STATUS_CANCELLED_VN]) // Bỏ qua lịch đã hủy
            ->with('dichVu')
            ->get(['id', 'thoi_gian_hen', 'dich_vu_id', 'trang_thai']);

        foreach ($rows as $row) {
            $existDateOnly = Carbon::parse($ngay_hen)->format('Y-m-d');
            $existStart = Carbon::parse($existDateOnly . ' ' . $row->thoi_gian_hen);
            $existDur = $row->dichVu ? ((int)($row->dichVu->thoi_gian_uoc_tinh ?? self::DEFAULT_APPT_MINUTES)) : self::DEFAULT_APPT_MINUTES;
            $existEnd = $existStart->copy()->addMinutes($existDur);
            if ($start->lt($existEnd) && $end->gt($existStart)) {
                return ['conflict' => true, 'details' => ['Bạn đã có lịch hẹn khác trong khung giờ này']];
            }
        }

        return ['conflict' => false, 'details' => []];
    }
}

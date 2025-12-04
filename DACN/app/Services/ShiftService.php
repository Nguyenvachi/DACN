<?php

namespace App\Services;

use App\Models\CaLamViecNhanVien;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class ShiftService
{
    /**
     * Kiểm tra xung đột ca làm việc
     *
     * @param int $nhanVienId
     * @param string $ngay (Y-m-d)
     * @param string $batDau (H:i)
     * @param string $ketThuc (H:i)
     * @param int|null $excludeId - ID ca cần loại trừ khi update
     * @return array ['conflict' => bool, 'message' => string]
     */
    public function checkConflict(int $nhanVienId, string $ngay, string $batDau, string $ketThuc, ?int $excludeId = null): array
    {
        $query = CaLamViecNhanVien::where('nhan_vien_id', $nhanVienId)
            ->where('ngay', $ngay);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $existingShifts = $query->get();

        foreach ($existingShifts as $shift) {
            // Kiểm tra overlap: (batDau < shift.ket_thuc) AND (ketThuc > shift.bat_dau)
            $newStart = Carbon::createFromFormat('H:i', $batDau);
            $newEnd = Carbon::createFromFormat('H:i', $ketThuc);
            $existingStart = Carbon::createFromFormat('H:i:s', $shift->bat_dau);
            $existingEnd = Carbon::createFromFormat('H:i:s', $shift->ket_thuc);

            if ($newStart->lt($existingEnd) && $newEnd->gt($existingStart)) {
                return [
                    'conflict' => true,
                    'message' => "Ca làm việc trùng với ca đã có: {$shift->bat_dau} - {$shift->ket_thuc}"
                ];
            }
        }

        return [
            'conflict' => false,
            'message' => ''
        ];
    }

    /**
     * Kiểm tra xung đột cho lịch lặp tuần (ví dụ: LichLamViec của bác sĩ)
     * Nếu có xung đột sẽ ném ValidationException để bám sát luồng xử lý của controller
     *
     * @param int $bacSiId
     * @param int $ngayTrongTuan
     * @param string $batDau (H:i)
     * @param string $ketThuc (H:i)
     * @param int|null $excludeId
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function checkConflictForRecurring(int $bacSiId, int $ngayTrongTuan, string $batDau, string $ketThuc, ?int $excludeId = null): void
    {
        $query = \App\Models\LichLamViec::where('bac_si_id', $bacSiId)
            ->where('ngay_trong_tuan', $ngayTrongTuan);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $existing = $query->get();

        $newStart = Carbon::createFromFormat('H:i', $batDau);
        $newEnd = Carbon::createFromFormat('H:i', $ketThuc);

        foreach ($existing as $shift) {
            $existingStart = $this->parseTimeFlexible($shift->thoi_gian_bat_dau);
            $existingEnd = $this->parseTimeFlexible($shift->thoi_gian_ket_thuc);

            if ($newStart->lt($existingEnd) && $newEnd->gt($existingStart)) {
                throw ValidationException::withMessages([
                    'thoi_gian_bat_dau' => ["Lịch làm việc trùng với lịch hiện có: {$shift->thoi_gian_bat_dau} - {$shift->thoi_gian_ket_thuc}"],
                ]);
            }
        }
    }

    /**
     * Parse time from string trying common formats ('H:i:s' then 'H:i')
     *
     * @param string $time
     * @return Carbon
     */
    private function parseTimeFlexible(string $time): Carbon
    {
        try {
            return Carbon::createFromFormat('H:i:s', $time);
        } catch (\Exception $e) {
            return Carbon::createFromFormat('H:i', $time);
        }
    }

    /**
     * Kiểm tra xung đột khi tạo lịch nghỉ (LichNghi)
     *
     * @param int $bacSiId
     * @param Carbon $startDateTime
     * @param Carbon $endDateTime
     * @param int|null $excludeId
     * @return void
     * @throws ValidationException
     */
    public function checkConflictForLeave(int $bacSiId, Carbon $startDateTime, Carbon $endDateTime, ?int $excludeId = null): void
    {
        // 1) Kiểm tra trùng với các lịch nghỉ đã tồn tại
        $query = \App\Models\LichNghi::where('bac_si_id', $bacSiId)
            ->whereDate('ngay', $startDateTime->toDateString());

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $existingLeaves = $query->get();

        foreach ($existingLeaves as $leave) {
            $existingStart = Carbon::parse($leave->ngay->format('Y-m-d') . ' ' . $leave->bat_dau);
            $existingEnd = Carbon::parse($leave->ngay->format('Y-m-d') . ' ' . $leave->ket_thuc);

            if ($startDateTime->lt($existingEnd) && $endDateTime->gt($existingStart)) {
                throw ValidationException::withMessages([
                    'ngay' => ["Lịch nghỉ trùng với lịch nghỉ đã có: {$leave->ngay->format('Y-m-d')} {$leave->bat_dau} - {$leave->ket_thuc}"],
                ]);
            }
        }

        // 2) Kiểm tra trùng với lịch làm việc định kỳ (LichLamViec) trong ngày đó
        $weekday = $startDateTime->dayOfWeek; // 0 (Sun) - 6 (Sat), phù hợp với quy ước trong LichLamViec

        $shifts = \App\Models\LichLamViec::where('bac_si_id', $bacSiId)
            ->where('ngay_trong_tuan', $weekday)
            ->get();

        foreach ($shifts as $shift) {
            // tạo các Carbon trên cùng ngày của request
            $existingStart = $startDateTime->copy()->setTimeFromTimeString(
                (string) $this->parseTimeFlexible($shift->thoi_gian_bat_dau)->format('H:i:s')
            );
            $existingEnd = $startDateTime->copy()->setTimeFromTimeString(
                (string) $this->parseTimeFlexible($shift->thoi_gian_ket_thuc)->format('H:i:s')
            );

            if ($startDateTime->lt($existingEnd) && $endDateTime->gt($existingStart)) {
                throw ValidationException::withMessages([
                    'ngay' => ["Thời gian nghỉ trùng với lịch làm việc: {$shift->thoi_gian_bat_dau} - {$shift->thoi_gian_ket_thuc} ({$this->weekdayLabel($weekday)})"],
                ]);
            }
        }
    }

    /**
     * Trả về label tên ngày từ weekday number
     */
    private function weekdayLabel(int $w): string
    {
        $map = [
            0 => 'Chủ nhật',
            1 => 'Thứ hai',
            2 => 'Thứ ba',
            3 => 'Thứ tư',
            4 => 'Thứ năm',
            5 => 'Thứ sáu',
            6 => 'Thứ bảy',
        ];

        return $map[$w] ?? (string) $w;
    }

    /**
     * Kiểm tra xung đột khi tạo/cập nhật Ca điều chỉnh (CaDieuChinhBacSi)
     * Kiểm tra:
     * - Trùng với lịch nghỉ
     * - Trùng với lịch làm việc định kỳ (nếu hành động là 'add')
     * - Trùng với các ca điều chỉnh khác
     *
     * @param int $bacSiId
     * @param Carbon $startDateTime
     * @param Carbon $endDateTime
     * @param int|null $excludeId
     * @return void
     * @throws ValidationException
     */
    public function checkConflictForAdjustment(int $bacSiId, Carbon $startDateTime, Carbon $endDateTime, ?int $excludeId = null): void
    {
        // 1) Kiểm tra trùng với lịch nghỉ trong ngày đó
        $lichNghis = \App\Models\LichNghi::where('bac_si_id', $bacSiId)
            ->whereDate('ngay', $startDateTime->toDateString())
            ->get();

        foreach ($lichNghis as $leave) {
            $existingStart = Carbon::parse($leave->ngay->format('Y-m-d') . ' ' . $leave->bat_dau);
            $existingEnd = Carbon::parse($leave->ngay->format('Y-m-d') . ' ' . $leave->ket_thuc);

            if ($startDateTime->lt($existingEnd) && $endDateTime->gt($existingStart)) {
                throw ValidationException::withMessages([
                    'ngay' => ["Ca điều chỉnh trùng với lịch nghỉ: {$leave->ngay->format('Y-m-d')} {$leave->bat_dau} - {$leave->ket_thuc}"],
                ]);
            }
        }

        // 2) Kiểm tra trùng với các ca điều chỉnh khác
        $query = \App\Models\CaDieuChinhBacSi::where('bac_si_id', $bacSiId)
            ->whereDate('ngay', $startDateTime->toDateString());

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $existingAdjustments = $query->get();

        foreach ($existingAdjustments as $adj) {
            $existingStart = Carbon::parse($adj->ngay->format('Y-m-d') . ' ' . $adj->gio_bat_dau);
            $existingEnd = Carbon::parse($adj->ngay->format('Y-m-d') . ' ' . $adj->gio_ket_thuc);

            if ($startDateTime->lt($existingEnd) && $endDateTime->gt($existingStart)) {
                throw ValidationException::withMessages([
                    'gio_bat_dau' => ["Ca điều chỉnh trùng với ca điều chỉnh đã có: {$adj->ngay->format('Y-m-d')} {$adj->gio_bat_dau} - {$adj->gio_ket_thuc}"],
                ]);
            }
        }
    }

    /**
     * Lấy tất cả các slot rảnh của bác sĩ trong khoảng thời gian từ startDate đến endDate
     * Trả về mảng các slot với định dạng ['date' => 'Y-m-d', 'start' => 'H:i', 'end' => 'H:i', 'phong_id' => ?int]
     *
     * @param int $bacSiId
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param int $slotDuration - thời gian 1 slot tính bằng phút (mặc định 30)
     * @return array
     */
    public function getAvailableSlots(int $bacSiId, Carbon $startDate, Carbon $endDate, int $slotDuration = 30): array
    {
        $slots = [];

        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $weekday = $currentDate->dayOfWeek;

            // Lấy lịch làm việc định kỳ trong ngày này
            $lichLamViecs = \App\Models\LichLamViec::where('bac_si_id', $bacSiId)
                ->where('ngay_trong_tuan', $weekday)
                ->get();

            foreach ($lichLamViecs as $lich) {
                $shiftStart = $currentDate->copy()->setTimeFromTimeString(
                    (string) $this->parseTimeFlexible($lich->thoi_gian_bat_dau)->format('H:i:s')
                );
                $shiftEnd = $currentDate->copy()->setTimeFromTimeString(
                    (string) $this->parseTimeFlexible($lich->thoi_gian_ket_thuc)->format('H:i:s')
                );

                // Kiểm tra xem ca này có bị lịch nghỉ hay ca điều chỉnh ảnh hưởng không
                if ($this->isShiftBlocked($bacSiId, $shiftStart, $shiftEnd)) {
                    continue;
                }

                // Chia ca làm việc thành các slot nhỏ
                $currentSlotStart = $shiftStart->copy();
                while ($currentSlotStart->copy()->addMinutes($slotDuration)->lte($shiftEnd)) {
                    $currentSlotEnd = $currentSlotStart->copy()->addMinutes($slotDuration);

                    // Kiểm tra slot này có bị đặt lịch chưa
                    if (!$this->isSlotBooked($bacSiId, $currentSlotStart, $currentSlotEnd)) {
                        $slots[] = [
                            'date' => $currentSlotStart->format('Y-m-d'),
                            'start' => $currentSlotStart->format('H:i'),
                            'end' => $currentSlotEnd->format('H:i'),
                            'phong_id' => $lich->phong_id,
                        ];
                    }

                    $currentSlotStart->addMinutes($slotDuration);
                }
            }

            // Thêm các ca điều chỉnh 'add' cho ngày hiện tại
            $caDieuChinhs = \App\Models\CaDieuChinhBacSi::where('bac_si_id', $bacSiId)
                ->whereDate('ngay', $currentDate->toDateString())
                ->where('hanh_dong', 'add')
                ->get();

            foreach ($caDieuChinhs as $ca) {
                $caStart = Carbon::parse($ca->ngay->format('Y-m-d') . ' ' . $ca->gio_bat_dau);
                $caEnd = Carbon::parse($ca->ngay->format('Y-m-d') . ' ' . $ca->gio_ket_thuc);

                $currentSlotStart = $caStart->copy();
                while ($currentSlotStart->copy()->addMinutes($slotDuration)->lte($caEnd)) {
                    $currentSlotEnd = $currentSlotStart->copy()->addMinutes($slotDuration);

                    if (!$this->isSlotBooked($bacSiId, $currentSlotStart, $currentSlotEnd)) {
                        $slots[] = [
                            'date' => $currentSlotStart->format('Y-m-d'),
                            'start' => $currentSlotStart->format('H:i'),
                            'end' => $currentSlotEnd->format('H:i'),
                            'phong_id' => null,
                        ];
                    }

                    $currentSlotStart->addMinutes($slotDuration);
                }
            }

            $currentDate->addDay();
        }

        return $slots;
    }

    /**
     * Kiểm tra xem một ca làm việc có bị chặn bởi lịch nghỉ hoặc ca điều chỉnh 'cancel' không
     *
     * @param int $bacSiId
     * @param Carbon $shiftStart
     * @param Carbon $shiftEnd
     * @return bool
     */
    private function isShiftBlocked(int $bacSiId, Carbon $shiftStart, Carbon $shiftEnd): bool
    {
        // Kiểm tra lịch nghỉ
        $lichNghis = \App\Models\LichNghi::where('bac_si_id', $bacSiId)
            ->whereDate('ngay', $shiftStart->toDateString())
            ->get();

        foreach ($lichNghis as $leave) {
            $leaveStart = Carbon::parse($leave->ngay->format('Y-m-d') . ' ' . $leave->bat_dau);
            $leaveEnd = Carbon::parse($leave->ngay->format('Y-m-d') . ' ' . $leave->ket_thuc);

            if ($shiftStart->lt($leaveEnd) && $shiftEnd->gt($leaveStart)) {
                return true;
            }
        }

        // Kiểm tra ca điều chỉnh 'cancel' hoặc 'modify'
        $caDieuChinhs = \App\Models\CaDieuChinhBacSi::where('bac_si_id', $bacSiId)
            ->whereDate('ngay', $shiftStart->toDateString())
            ->whereIn('hanh_dong', ['cancel', 'modify'])
            ->get();

        foreach ($caDieuChinhs as $adj) {
            $adjStart = Carbon::parse($adj->ngay->format('Y-m-d') . ' ' . $adj->gio_bat_dau);
            $adjEnd = Carbon::parse($adj->ngay->format('Y-m-d') . ' ' . $adj->gio_ket_thuc);

            if ($shiftStart->lt($adjEnd) && $shiftEnd->gt($adjStart)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Kiểm tra xem slot có bị đặt lịch chưa
     *
     * @param int $bacSiId
     * @param Carbon $slotStart
     * @param Carbon $slotEnd
     * @return bool
     */
    private function isSlotBooked(int $bacSiId, Carbon $slotStart, Carbon $slotEnd): bool
    {
        $bookedAppointments = \App\Models\LichHen::where('bac_si_id', $bacSiId)
            ->whereDate('ngay_hen', $slotStart->toDateString())
            ->whereNotIn('trang_thai', ['Đã hủy', 'Hoàn thành'])
            ->get();

        foreach ($bookedAppointments as $appointment) {
            // Kết hợp ngay_hen và thoi_gian_hen
            $appointmentStart = Carbon::parse($appointment->ngay_hen->format('Y-m-d') . ' ' . $appointment->thoi_gian_hen);
            // Giả sử mỗi lịch hẹn kéo dài slotDuration phút (30 phút)
            $appointmentEnd = $appointmentStart->copy()->addMinutes(30);

            if ($slotStart->lt($appointmentEnd) && $slotEnd->gt($appointmentStart)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Lấy N slot kế tiếp có sẵn của bác sĩ
     *
     * @param int $bacSiId
     * @param int $count - số lượng slot cần lấy
     * @param int $slotDuration - thời gian 1 slot (phút)
     * @return array
     */
    public function getNextAvailableSlots(int $bacSiId, int $count = 10, int $slotDuration = 30): array
    {
        $startDate = Carbon::now();
        $endDate = Carbon::now()->addDays(30); // Tìm trong vòng 30 ngày
        $slots = $this->getAvailableSlots($bacSiId, $startDate, $endDate, $slotDuration);

        return array_slice($slots, 0, $count);
    }

    /**
     * Kiểm tra xem ca điều chỉnh (modify/cancel) có nằm trong khung lịch làm việc không
     * Nếu không nằm trong khung lịch làm việc, ném ValidationException
     *
     * @param int $bacSiId
     * @param Carbon $startDateTime
     * @param Carbon $endDateTime
     * @return void
     * @throws ValidationException
     */
    public function validateWithinWorkSchedule(int $bacSiId, Carbon $startDateTime, Carbon $endDateTime): void
    {
        $weekday = $startDateTime->dayOfWeek;

        // Lấy tất cả lịch làm việc trong ngày đó
        $lichLamViecs = \App\Models\LichLamViec::where('bac_si_id', $bacSiId)
            ->where('ngay_trong_tuan', $weekday)
            ->get();

        if ($lichLamViecs->isEmpty()) {
            throw ValidationException::withMessages([
                'ngay' => ['Bác sĩ không có lịch làm việc trong ngày này.'],
            ]);
        }

        // Kiểm tra xem thời gian có nằm trong bất kỳ ca làm việc nào không
        $withinSchedule = false;

        foreach ($lichLamViecs as $lich) {
            $shiftStart = $startDateTime->copy()->setTimeFromTimeString(
                (string) $this->parseTimeFlexible($lich->thoi_gian_bat_dau)->format('H:i:s')
            );
            $shiftEnd = $startDateTime->copy()->setTimeFromTimeString(
                (string) $this->parseTimeFlexible($lich->thoi_gian_ket_thuc)->format('H:i:s')
            );

            // Kiểm tra nếu thời gian điều chỉnh nằm hoàn toàn trong ca làm việc
            if ($startDateTime->gte($shiftStart) && $endDateTime->lte($shiftEnd)) {
                $withinSchedule = true;
                break;
            }
        }

        if (!$withinSchedule) {
            throw ValidationException::withMessages([
                'gio_bat_dau' => ['Ca điều chỉnh phải nằm trong khung lịch làm việc của bác sĩ.'],
            ]);
        }
    }
}


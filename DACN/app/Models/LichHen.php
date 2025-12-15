<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class LichHen extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bac_si_id',
        'dich_vu_id',
        'tong_tien',
        'ngay_hen',
        'thoi_gian_hen',
        'ghi_chu',
        'trang_thai',
        'checked_in_by',
        'checked_in_at',
        'thoi_gian_bat_dau_kham',
        'completed_at',
        'payment_status',
        'payment_method',
        'paid_at',
    ];

    // Các trạng thái trong luồng nghiệp vụ y tế chuẩn
    const STATUS_PENDING = 'pending';           // Chờ xác nhận
    const STATUS_CONFIRMED = 'confirmed';       // Đã xác nhận
    const STATUS_CHECKED_IN = 'checked_in';     // Đã check-in
    const STATUS_IN_PROGRESS = 'in_progress';   // Đang khám
    const STATUS_COMPLETED = 'completed';       // Hoàn thành
    const STATUS_CANCELLED = 'cancelled';       // Đã hủy

    // Vietnamese display labels (kept for consistency with existing DB values)
    const STATUS_PENDING_VN = 'Chờ xác nhận';
    const STATUS_CONFIRMED_VN = 'Đã xác nhận';
    const STATUS_CHECKED_IN_VN = 'Đã check-in';
    const STATUS_IN_PROGRESS_VN = 'Đang khám';
    const STATUS_COMPLETED_VN = 'Hoàn thành';
    const STATUS_CANCELLED_VN = 'Đã hủy';

    /**
     * Map internal keys (english) to Vietnamese labels.
     * Use this when you need a canonical VN label for a given internal key.
     */
    public static function statusLabelMap(): array
    {
        return [
            self::STATUS_PENDING => self::STATUS_PENDING_VN,
            self::STATUS_CONFIRMED => self::STATUS_CONFIRMED_VN,
            self::STATUS_CHECKED_IN => self::STATUS_CHECKED_IN_VN,
            self::STATUS_IN_PROGRESS => self::STATUS_IN_PROGRESS_VN,
            self::STATUS_COMPLETED => self::STATUS_COMPLETED_VN,
            self::STATUS_CANCELLED => self::STATUS_CANCELLED_VN,
        ];
    }

    /**
     * Normalize a status value (english or VN) to VN label.
     */
    public static function toVnLabel(string $value): string
    {
        $value = trim((string)$value);
        // If already a VN label, return as-is
        $vnValues = array_values(self::statusLabelMap());
        if (in_array($value, $vnValues, true)) {
            return $value;
        }

        // Otherwise try to map by english key
        $key = mb_strtolower($value);
        $map = array_change_key_case(self::statusLabelMap(), CASE_LOWER);
        return $map[$key] ?? $value;
    }

    protected $casts = [
        'ngay_hen' => 'date',
        'tong_tien' => 'decimal:2',
        'paid_at' => 'datetime',
        'checked_in_at' => 'datetime',
        'thoi_gian_bat_dau_kham' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Kiểm tra xem lịch hẹn có thể check-in (staff) hay không
     */
    public function isCheckinAllowed(): bool
    {
        // Check-in chỉ cho phép khi đã xác nhận và ngày hẹn là hôm nay
        if ($this->trang_thai !== self::STATUS_CONFIRMED_VN) return false;
        if (! $this->ngay_hen) return false;
        try {
            $date = \Carbon\Carbon::parse($this->ngay_hen);
            return $date->isSameDay(today());
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Kiểm tra bác sĩ có thể bắt đầu khám cho lịch hẹn hay không
     */
    public function canStartExam(): bool
    {
        return in_array($this->trang_thai, [self::STATUS_CHECKED_IN_VN, self::STATUS_CONFIRMED_VN, self::STATUS_IN_PROGRESS_VN], true);
    }

    /**
     * Kiểm tra có thể hoàn thành khám hay không
     */
    public function canCompleteExam(): bool
    {
        return $this->trang_thai === self::STATUS_IN_PROGRESS_VN;
    }

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Who performed the check-in
     */
    public function checkedInBy()
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }

    public function bacSi()
    {
        return $this->belongsTo(BacSi::class);
    }

    public function dichVu()
    {
        return $this->belongsTo(\App\Models\DichVu::class, 'dich_vu_id');
    }

    public function hoaDon()
    {
        return $this->hasOne(\App\Models\HoaDon::class);
    }

    public function danhGia()
    {
        return $this->hasOne(DanhGia::class);
    }

    /**
     * Relation to patient's medical record (Bệnh án) for this appointment.
     * Some controllers call $lichHen->load('benhAn') so we expose that name here.
     */
    public function benhAn()
    {
        return $this->hasOne(BenhAn::class, 'lich_hen_id');
    }

    public function conversation()
    {
        return $this->hasOne(Conversation::class, 'lich_hen_id');
    }

    public function setStatusAttribute($value)
    {
        $aliases = [
            'pending' => 'Chờ xác nhận',
            'cho_xac_nhan' => 'Chờ xác nhận',
            'cho' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'approved' => 'Đã xác nhận',
            'canceled' => 'Đã hủy',
            'cancelled' => 'Đã hủy',
        ];
        $key = mb_strtolower(trim((string) $value));
        $this->attributes['status'] = $aliases[$key] ?? ($value ?: 'Chờ xác nhận');
    }

    public function setTrangThaiAttribute($value)
    {
        // Normalize input status to Vietnamese label used across the app
        $this->attributes['trang_thai'] = $value ? self::toVnLabel($value) : self::STATUS_PENDING_VN;
    }

    public function getThanhToanTrangThaiAttribute()
    {
        return optional($this->hoaDon)->trang_thai ?? \App\Models\HoaDon::STATUS_UNPAID_VN;
    }

    /** Gợi ý tổng tiền nếu chưa có hóa đơn (dựa theo dịch vụ) */
    public function getTongTienDeXuatAttribute()
    {
        return optional($this->dichVu)->gia ?? 0;
    }

    public function getIsPaidAttribute(): bool
    {
        return ($this->payment_status === \App\Models\HoaDon::STATUS_PAID_VN)
            || (optional($this->hoaDon)->trang_thai === \App\Models\HoaDon::STATUS_PAID_VN);
    }

    public function getPaymentLabelAttribute(): string
    {
        return $this->payment_status
            ?? (optional($this->hoaDon)->trang_thai ?? \App\Models\HoaDon::STATUS_UNPAID_VN);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaiKham extends Model
{
    use SoftDeletes;

    protected $table = 'tai_khams';

    public const STATUS_PENDING_VN = 'Chờ xác nhận';
    public const STATUS_CONFIRMED_VN = 'Đã xác nhận';
    public const STATUS_BOOKED_VN = 'Đã đặt lịch';
    public const STATUS_COMPLETED_VN = 'Hoàn thành';
    public const STATUS_CANCELLED_VN = 'Đã hủy';

    public const STATUS_LABELS = [
        self::STATUS_PENDING_VN => self::STATUS_PENDING_VN,
        self::STATUS_CONFIRMED_VN => self::STATUS_CONFIRMED_VN,
        self::STATUS_BOOKED_VN => self::STATUS_BOOKED_VN,
        self::STATUS_COMPLETED_VN => self::STATUS_COMPLETED_VN,
        self::STATUS_CANCELLED_VN => self::STATUS_CANCELLED_VN,
    ];

    protected $fillable = [
        'benh_an_id',
        'user_id',
        'bac_si_id',
        'lich_hen_id',
        'ngay_tai_kham',
        'thoi_gian_tai_kham',
        'so_ngay_du_kien',
        'ly_do',
        'ghi_chu',
        'trang_thai',
        'created_by_role',
    ];

    protected $casts = [
        'ngay_tai_kham' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function benhAn()
    {
        return $this->belongsTo(BenhAn::class, 'benh_an_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bacSi()
    {
        return $this->belongsTo(BacSi::class, 'bac_si_id');
    }

    public function lichHen()
    {
        return $this->belongsTo(LichHen::class, 'lich_hen_id');
    }

    public function getTrangThaiTextAttribute(): string
    {
        $value = $this->trang_thai ?: '---';
        return self::STATUS_LABELS[$value] ?? $value;
    }

    public function getTrangThaiBadgeClassAttribute(): string
    {
        return match ($this->trang_thai) {
            self::STATUS_PENDING_VN => 'bg-secondary',
            self::STATUS_CONFIRMED_VN => 'bg-info',
            self::STATUS_BOOKED_VN => 'bg-primary',
            self::STATUS_COMPLETED_VN => 'bg-success',
            self::STATUS_CANCELLED_VN => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    public function getIsLockedAttribute(): bool
    {
        return in_array($this->trang_thai, [self::STATUS_COMPLETED_VN, self::STATUS_CANCELLED_VN], true);
    }
}

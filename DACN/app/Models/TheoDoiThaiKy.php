<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TheoDoiThaiKy extends Model
{
    use SoftDeletes;

    public const ALERT_BP_SYS_HIGH = 140;
    public const ALERT_BP_DIA_HIGH = 90;
    public const ALERT_BP_SYS_LOW = 90;
    public const ALERT_BP_DIA_LOW = 60;

    public const ALERT_FHR_LOW = 110;
    public const ALERT_FHR_HIGH = 160;

    public const ALERT_GLUCOSE_LOW = 3.5;
    public const ALERT_GLUCOSE_HIGH = 7.8;

    public const ALERT_HB_LOW = 11.0;
    public const ALERT_HB_HIGH = 16.0;

    protected $table = 'theo_doi_thai_kys';

    protected $fillable = [
        'benh_an_id',
        'user_id',
        'bac_si_id',
        'ngay_theo_doi',
        'tuan_thai',
        'can_nang_kg',
        'huyet_ap_tam_thu',
        'huyet_ap_tam_truong',
        'nhip_tim_thai',
        'duong_huyet',
        'huyet_sac_to',
        'trieu_chung',
        'ghi_chu',
        'nhan_xet',
        'file_path',
        'disk',
        'trang_thai',
    ];

    protected $casts = [
        'ngay_theo_doi' => 'date',
        'can_nang_kg' => 'decimal:2',
        'duong_huyet' => 'decimal:2',
        'huyet_sac_to' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_REVIEWED = 'reviewed';
    public const STATUS_RECORDED = 'recorded';
    public const STATUS_ARCHIVED = 'archived';

    public const STATUS_LABELS = [
        self::STATUS_SUBMITTED => 'Đã gửi',
        self::STATUS_REVIEWED => 'Đã duyệt',
        self::STATUS_RECORDED => 'Đã ghi nhận',
        self::STATUS_ARCHIVED => 'Lưu trữ',
    ];

    public function getTrangThaiTextAttribute()
    {
        return self::STATUS_LABELS[$this->trang_thai] ?? ($this->trang_thai ?? '---');
    }

    public function getTrangThaiBadgeClassAttribute()
    {
        return match ($this->trang_thai) {
            self::STATUS_SUBMITTED => 'bg-secondary',
            self::STATUS_REVIEWED => 'bg-info',
            self::STATUS_RECORDED => 'bg-success',
            self::STATUS_ARCHIVED => 'bg-dark',
            default => 'bg-secondary',
        };
    }

    public function getCanhBaoItemsAttribute(): array
    {
        $items = [];

        $add = static function (string $key, string $message, string $severity = 'warning') use (&$items): void {
            $items[] = [
                'key' => $key,
                'message' => $message,
                'severity' => $severity,
            ];
        };

        $sys = $this->huyet_ap_tam_thu !== null ? (int) $this->huyet_ap_tam_thu : null;
        $dia = $this->huyet_ap_tam_truong !== null ? (int) $this->huyet_ap_tam_truong : null;
        if ($sys !== null && $dia !== null) {
            if ($sys >= self::ALERT_BP_SYS_HIGH || $dia >= self::ALERT_BP_DIA_HIGH) {
                $add('bp_high', 'Huyết áp cao', 'danger');
            }
            if ($sys <= self::ALERT_BP_SYS_LOW || $dia <= self::ALERT_BP_DIA_LOW) {
                $add('bp_low', 'Huyết áp thấp', 'warning');
            }
        }

        $fhr = $this->nhip_tim_thai !== null ? (int) $this->nhip_tim_thai : null;
        if ($fhr !== null) {
            if ($fhr < self::ALERT_FHR_LOW) {
                $add('fhr_low', 'Nhịp tim thai thấp', 'danger');
            }
            if ($fhr > self::ALERT_FHR_HIGH) {
                $add('fhr_high', 'Nhịp tim thai cao', 'danger');
            }
        }

        $glucose = $this->duong_huyet !== null ? (float) $this->duong_huyet : null;
        if ($glucose !== null) {
            if ($glucose < self::ALERT_GLUCOSE_LOW) {
                $add('glucose_low', 'Đường huyết thấp', 'warning');
            }
            if ($glucose > self::ALERT_GLUCOSE_HIGH) {
                $add('glucose_high', 'Đường huyết cao', 'warning');
            }
        }

        $hb = $this->huyet_sac_to !== null ? (float) $this->huyet_sac_to : null;
        if ($hb !== null) {
            if ($hb < self::ALERT_HB_LOW) {
                $add('hb_low', 'Huyết sắc tố thấp', 'warning');
            }
            if ($hb > self::ALERT_HB_HIGH) {
                $add('hb_high', 'Huyết sắc tố cao', 'warning');
            }
        }

        return $items;
    }

    public function getHasCanhBaoAttribute(): bool
    {
        return count($this->canh_bao_items) > 0;
    }

    public function getCanhBaoSummaryAttribute(): string
    {
        $messages = array_map(static fn (array $i) => (string) ($i['message'] ?? ''), $this->canh_bao_items);
        $messages = array_values(array_filter($messages, static fn (string $m) => $m !== ''));

        return implode('; ', $messages);
    }

    public function getCanhBaoBadgeClassAttribute(): string
    {
        foreach ($this->canh_bao_items as $item) {
            if (($item['severity'] ?? null) === 'danger') {
                return 'bg-danger';
            }
        }

        return $this->has_canh_bao ? 'bg-warning text-dark' : 'bg-success';
    }

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
}

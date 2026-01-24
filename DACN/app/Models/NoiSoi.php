<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoiSoi extends Model
{
    protected $table = 'noi_sois';

    protected $fillable = [
        'user_id',
        'benh_an_id',
        'bac_si_chi_dinh_id',
        'bac_si_noi_soi_id',
        'phong_id',
        'loai_noi_soi_id',
        'loai',
        'mo_ta',
        'gia',
        'file_path',
        'disk',
        'trang_thai',
        'nhan_xet',
        'ket_qua',
        'ngay_chi_dinh',
        'ngay_thuc_hien',
        'ngay_hoan_thanh',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'ngay_chi_dinh' => 'datetime',
        'ngay_thuc_hien' => 'datetime',
        'ngay_hoan_thanh' => 'datetime',
        'gia' => 'decimal:2',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_COMPLETED = 'completed';

    public function benhAn()
    {
        return $this->belongsTo(BenhAn::class, 'benh_an_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bacSiChiDinh()
    {
        return $this->belongsTo(BacSi::class, 'bac_si_chi_dinh_id');
    }

    public function bacSiNoiSoi()
    {
        return $this->belongsTo(BacSi::class, 'bac_si_noi_soi_id');
    }

    public function phong()
    {
        return $this->belongsTo(Phong::class, 'phong_id');
    }

    public function loaiNoiSoi()
    {
        return $this->belongsTo(LoaiNoiSoi::class, 'loai_noi_soi_id');
    }

    public function scopePending($query)
    {
        return $query->where('trang_thai', self::STATUS_PENDING);
    }

    public function scopeProcessing($query)
    {
        return $query->where('trang_thai', self::STATUS_PROCESSING);
    }

    public function scopeCompleted($query)
    {
        return $query->where('trang_thai', self::STATUS_COMPLETED);
    }

    public function scopeByPatient($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByBacSiChiDinh($query, $bacSiId)
    {
        return $query->where('bac_si_chi_dinh_id', $bacSiId);
    }

    public function getTrangThaiTextAttribute()
    {
        return match ($this->trang_thai) {
            self::STATUS_PENDING => 'Chờ thực hiện',
            self::STATUS_PROCESSING => 'Đang xử lý',
            self::STATUS_COMPLETED => 'Đã có kết quả',
            default => (string) $this->trang_thai,
        };
    }

    public function getDownloadUrl()
    {
        if (! $this->file_path) {
            return null;
        }

        $role = 'patient';
        if (auth()->check() && auth()->user()) {
            $userRole = auth()->user()->role;
            if ($userRole === 'doctor') $role = 'doctor';
            elseif ($userRole === 'admin') $role = 'admin';
            elseif ($userRole === 'staff') $role = 'staff';
        }

        return \Illuminate\Support\Facades\URL::temporarySignedRoute(
            $role . '.benhan.noisoi.download',
            now()->addMinutes(10),
            ['noiSoi' => $this->id]
        );
    }

    public function hasResult(): bool
    {
        return $this->trang_thai === self::STATUS_COMPLETED && !empty($this->file_path);
    }

    public function canUploadResult(): bool
    {
        return in_array($this->trang_thai, [self::STATUS_PENDING, self::STATUS_PROCESSING], true);
    }

    public function getStatusBadgeColor()
    {
        return match ($this->trang_thai) {
            self::STATUS_PENDING => 'secondary',
            self::STATUS_PROCESSING => 'warning',
            self::STATUS_COMPLETED => 'success',
            default => 'secondary',
        };
    }

    public function getStatusIcon()
    {
        return match ($this->trang_thai) {
            self::STATUS_PENDING => 'fa-hourglass-start',
            self::STATUS_PROCESSING => 'fa-spinner fa-spin',
            self::STATUS_COMPLETED => 'fa-check-circle',
            default => 'fa-question-circle',
        };
    }

    public function getDiskNameAttribute()
    {
        return $this->disk ?? 'benh_an_private';
    }
}

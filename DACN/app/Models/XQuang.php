<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class XQuang extends Model
{
    protected $table = 'x_quangs';

    protected $fillable = [
        'benh_an_id',
        'user_id',
        'bac_si_id',
        'loai_x_quang_id',
        'loai',
        'mo_ta',
        'gia',
        'ngay_chi_dinh',
        'file_path',
        'disk',
        'trang_thai',
        'nhan_xet',
        'ket_qua',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'ngay_chi_dinh' => 'datetime',
        'gia' => 'decimal:2',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';

    public function benhAn()
    {
        return $this->belongsTo(BenhAn::class, 'benh_an_id');
    }

    public function bacSi()
    {
        return $this->belongsTo(BacSi::class, 'bac_si_id');
    }

    public function loaiXQuang()
    {
        return $this->belongsTo(LoaiXQuang::class, 'loai_x_quang_id');
    }

    /**
     * User (bệnh nhân) - thuận tiện cho query/filter
     * Lưu ý: bảng x_quangs có cột user_id
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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

    public function scopeByBacSi($query, $bacSiId)
    {
        return $query->where('bac_si_id', $bacSiId);
    }

    public function scopeByPatient($query, $userId)
    {
        return $query->whereHas('benhAn', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

    public function getTrangThaiTextAttribute()
    {
        return match ($this->trang_thai) {
            self::STATUS_PENDING => 'Chờ thực hiện',
            self::STATUS_PROCESSING => 'Đang xử lý',
            self::STATUS_COMPLETED => 'Đã có kết quả',
            default => $this->trang_thai,
        };
    }

    /**
     * Lấy URL file kết quả (parity với XetNghiem)
     */
    public function getFileUrl()
    {
        if (! $this->file_path) {
            return null;
        }

        // NOTE: disk mặc định là private; URL public có thể không dùng được.
        // Helper này giữ tương thích UI; download nên dùng signed URL.
        return asset('storage/' . $this->file_path);
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
            $role . '.benhan.xquang.download',
            now()->addMinutes(10),
            ['xQuang' => $this->id]
        );
    }

    public function hasResult()
    {
        return $this->trang_thai === self::STATUS_COMPLETED && $this->file_path;
    }

    public function canUploadResult()
    {
        return in_array($this->trang_thai, [self::STATUS_PENDING, self::STATUS_PROCESSING]);
    }

    /**
     * Màu badge theo trạng thái (parity với XetNghiem)
     */
    public function getStatusBadgeColor()
    {
        return match ($this->trang_thai) {
            self::STATUS_PENDING => 'secondary',
            self::STATUS_PROCESSING => 'warning',
            self::STATUS_COMPLETED => 'success',
            default => 'secondary',
        };
    }

    /**
     * Icon theo trạng thái (parity với XetNghiem)
     */
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

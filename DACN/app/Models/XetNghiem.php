<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class XetNghiem extends Model
{
    protected $fillable = [
        'benh_an_id',
        'bac_si_id',
        'loai_xet_nghiem_id',
        'loai',
        'mo_ta',
        'gia',
        'file_path',
        'disk',
        'trang_thai',
        'nhan_xet',
        'ket_qua',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'gia' => 'decimal:2',
    ];

    // ============================================
    // CONSTANTS - Trạng thái xét nghiệm
    // ============================================
    const STATUS_PENDING = 'pending';       // Chờ thực hiện
    const STATUS_PROCESSING = 'processing'; // Đang xử lý
    const STATUS_COMPLETED = 'completed';   // Đã có kết quả

    // ============================================
    // RELATIONSHIPS
    // ============================================

    /**
     * Bệnh án mà xét nghiệm này thuộc về
     */
    public function benhAn()
    {
        return $this->belongsTo(BenhAn::class, 'benh_an_id');
    }

    /**
     * Bác sĩ chỉ định xét nghiệm
     */
    public function bacSi()
    {
        return $this->belongsTo(BacSi::class, 'bac_si_id');
    }

    /**
     * Loại xét nghiệm (master) mà xét nghiệm này thuộc về
     */
    public function loaiXetNghiem()
    {
        return $this->belongsTo(LoaiXetNghiem::class, 'loai_xet_nghiem_id');
    }

    /**
     * Lấy user (bệnh nhân) qua bệnh án
     */
    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            BenhAn::class,
            'id',           // Foreign key on benh_ans
            'id',           // Foreign key on users
            'benh_an_id',   // Local key on xet_nghiems
            'user_id'       // Local key on benh_ans
        );
    }

    // ============================================
    // SCOPES - Query helpers
    // ============================================

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
        return $query->whereHas('benhAn', function($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

    // ============================================
    // ACCESSORS & HELPERS
    // ============================================

    /**
     * Lấy tên trạng thái tiếng Việt
     */
    public function getTrangThaiTextAttribute()
    {
        return match($this->trang_thai) {
            self::STATUS_PENDING => 'Chờ thực hiện',
            self::STATUS_PROCESSING => 'Đang xử lý',
            self::STATUS_COMPLETED => 'Đã có kết quả',
            default => $this->trang_thai,
        };
    }

    /**
     * Lấy URL file kết quả
     */
    public function getFileUrl()
    {
        if (!$this->file_path) {
            return null;
        }

        $disk = $this->disk ?? 'benh_an_private';

        // Sử dụng asset hoặc Storage::url
        return asset('storage/' . $this->file_path);
    }

    /**
     * Lấy signed URL để download file
     */
    public function getDownloadUrl()
    {
        if (!$this->file_path) {
            return null;
        }

        // Xác định role của user hiện tại
        $role = 'patient';
        if (auth()->check() && auth()->user()) {
            $userRole = auth()->user()->role;
            if ($userRole === 'doctor') $role = 'doctor';
            elseif ($userRole === 'admin') $role = 'admin';
            elseif ($userRole === 'staff') $role = 'staff';
        }

        return \Illuminate\Support\Facades\URL::temporarySignedRoute(
            $role . '.benhan.xetnghiem.download',
            now()->addMinutes(10),
            ['xetNghiem' => $this->id]
        );
    }

    /**
     * Kiểm tra đã có kết quả chưa
     */
    public function hasResult()
    {
        return $this->trang_thai === self::STATUS_COMPLETED && $this->file_path;
    }

    /**
     * Kiểm tra có thể upload kết quả không
     */
    public function canUploadResult()
    {
        return in_array($this->trang_thai, [self::STATUS_PENDING, self::STATUS_PROCESSING]);
    }

    /**
     * Lấy màu badge theo trạng thái
     */
    public function getStatusBadgeColor()
    {
        return match($this->trang_thai) {
            self::STATUS_PENDING => 'secondary',
            self::STATUS_PROCESSING => 'warning',
            self::STATUS_COMPLETED => 'success',
            default => 'secondary',
        };
    }

    /**
     * Lấy icon theo trạng thái
     */
    public function getStatusIcon()
    {
        return match($this->trang_thai) {
            self::STATUS_PENDING => 'fa-hourglass-start',
            self::STATUS_PROCESSING => 'fa-spinner fa-spin',
            self::STATUS_COMPLETED => 'fa-check-circle',
            default => 'fa-question-circle',
        };
    }

    // Accessor để lấy tên disk (fallback về benh_an_private nếu null)
    public function getDiskNameAttribute()
    {
        return $this->disk ?? 'benh_an_private';
    }
}

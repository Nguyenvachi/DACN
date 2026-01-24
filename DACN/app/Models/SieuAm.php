<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class SieuAm extends Model
{
    protected static ?bool $hasLegacyBacSiIdColumn = null;

    protected static function hasLegacyBacSiIdColumn(): bool
    {
        if (self::$hasLegacyBacSiIdColumn === null) {
            try {
                self::$hasLegacyBacSiIdColumn = Schema::hasColumn('sieu_ams', 'bac_si_id');
            } catch (\Throwable $e) {
                self::$hasLegacyBacSiIdColumn = false;
            }
        }

        return (bool) self::$hasLegacyBacSiIdColumn;
    }
    protected $fillable = [
        'user_id',
        'benh_an_id',
        'bac_si_chi_dinh_id',
        'bac_si_id', // Alias for bac_si_chi_dinh_id (backward compatibility)
        'loai_sieu_am_id',
        'bac_si_sieu_am_id',
        'phong_id',
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
        'gia' => 'decimal:2',
        'ngay_chi_dinh' => 'datetime',
        'ngay_thuc_hien' => 'datetime',
        'ngay_hoan_thanh' => 'datetime',
    ];

    // ============================================
    // CONSTANTS - Trạng thái siêu âm
    // ============================================
    const STATUS_PENDING = 'pending';       // Chờ thực hiện
    const STATUS_PROCESSING = 'processing'; // Đang xử lý
    const STATUS_COMPLETED = 'completed';   // Đã có kết quả

    // ============================================
    // RELATIONSHIPS
    // ============================================

    /**
     * Bệnh nhân (User)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Bệnh án
     */
    public function benhAn()
    {
        return $this->belongsTo(BenhAn::class);
    }

    /**
     * Bác sĩ chỉ định siêu âm
     */
    public function bacSiChiDinh()
    {
        return $this->belongsTo(BacSi::class, 'bac_si_chi_dinh_id');
    }

    /**
     * Alias cho bacSiChiDinh (backward compatibility)
     */
    public function bacSi()
    {
        return $this->belongsTo(BacSi::class, 'bac_si_chi_dinh_id');
    }

    /**
     * Bác sĩ thực hiện siêu âm (có thể khác bác sĩ chỉ định)
     */
    public function bacSiSieuAm()
    {
        return $this->belongsTo(BacSi::class, 'bac_si_sieu_am_id');
    }

    /**
     * Loại siêu âm
     */
    public function loaiSieuAm()
    {
        return $this->belongsTo(LoaiSieuAm::class);
    }

    /**
     * Phòng siêu âm
     */
    public function phong()
    {
        return $this->belongsTo(Phong::class);
    }

    // ============================================
    // SCOPES
    // ============================================
    public function scopeByBacSi($query, $bacSiId)
    {
        // DB chuẩn hiện tại dùng bac_si_chi_dinh_id; bac_si_id là alias legacy (có thể không tồn tại cột)
        return $query->where(function ($q) use ($bacSiId) {
            $q->where('bac_si_chi_dinh_id', $bacSiId);
            if (self::hasLegacyBacSiIdColumn()) {
                $q->orWhere('bac_si_id', $bacSiId);
            }
        });
    }

    public function scopePending($query)
    {
        return $query->where('trang_thai', self::STATUS_PENDING);
    }

    public function scopeCompleted($query)
    {
        return $query->where('trang_thai', self::STATUS_COMPLETED);
    }

    // ============================================
    // ACCESSORS & HELPERS
    // ============================================
    public function getTrangThaiTextAttribute()
    {
        return match($this->trang_thai) {
            self::STATUS_PENDING => 'Chờ thực hiện',
            self::STATUS_PROCESSING => 'Đang xử lý',
            self::STATUS_COMPLETED => 'Đã có kết quả',
            default => 'Không xác định',
        };
    }

    public function getTrangThaiBadgeClassAttribute()
    {
        return match($this->trang_thai) {
            self::STATUS_PENDING => 'bg-warning',
            self::STATUS_PROCESSING => 'bg-info',
            self::STATUS_COMPLETED => 'bg-success',
            default => 'bg-secondary',
        };
    }

    public function getFileUrlAttribute()
    {
        if (!$this->file_path) return null;

        $disk = $this->disk ?? 'sieu_am_private';
        return Storage::disk($disk)->url($this->file_path);
    }

    /**
     * Lấy signed URL để download file (đồng bộ chuẩn Xét nghiệm)
     */
    public function getDownloadUrl()
    {
        if (!$this->file_path) {
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
            $role . '.benhan.sieuam.download',
            now()->addMinutes(10),
            ['sieuAm' => $this->id]
        );
    }

    /**
     * Lọc theo bệnh nhân
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Lọc theo bác sĩ chỉ định
     */
    public function scopeByBacSiChiDinh($query, $bacSiId)
    {
        return $query->where('bac_si_chi_dinh_id', $bacSiId);
    }

    /**
     * Đang xử lý
     */
    public function scopeProcessing($query)
    {
        return $query->where('trang_thai', self::STATUS_PROCESSING);
    }

    /**
     * Format giá
     */
    public function getGiaFormattedAttribute(): string
    {
        return number_format($this->gia, 0, ',', '.') . 'đ';
    }

    /**
     * Kiểm tra có file hay không
     */
    public function hasFile(): bool
    {
        return !empty($this->file_path);
    }

    /**
     * Kiểm tra quyền xem/download file
     */
    public function canDownload($user): bool
    {
        if (!$user) return false;

        // Admin có thể xem tất cả
        if ($user->hasRole('admin')) return true;

        // Bác sĩ chỉ định được xem
        $bacSi = BacSi::where('user_id', $user->id)->first();
        if ($bacSi && $this->bac_si_chi_dinh_id === $bacSi->id) {
            return true;
        }

        // Bác sĩ thực hiện siêu âm được xem
        if ($bacSi && $this->bac_si_sieu_am_id === $bacSi->id) {
            return true;
        }

        // Bệnh nhân chỉ xem siêu âm của mình
        if ($user->hasRole('patient')) {
            return $this->user_id === $user->id;
        }

        // Staff được xem tất cả
        if ($user->hasRole('staff')) {
            return true;
        }

        return false;
    }

    /**
     * Kiểm tra file có tồn tại trong storage không
     */
    public function fileExists(): bool
    {
        if (!$this->file_path) return false;

        $disk = $this->disk ?? 'sieu_am_private';
        return Storage::disk($disk)->exists($this->file_path);
    }
}

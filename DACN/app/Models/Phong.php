<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Phong - Quản lý phòng khám
 * Parent file: app/Models/Phong.php
 */
class Phong extends Model
{
    protected $fillable = [
        'ten',
        'loai',
        'mo_ta',
        'trang_thai',  // MỞ RỘNG: Thêm trạng thái (Parent file)
        'vi_tri',      // MỞ RỘNG: Vị trí phòng (Parent file)
        'dien_tich',   // MỞ RỘNG: Diện tích (Parent file)
        'suc_chua',    // MỞ RỘNG: Sức chứa tối đa (Parent file)
    ];

    protected $casts = [
        'suc_chua' => 'integer',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Bác sĩ thuộc phòng này (many-to-many)
     */
    public function bacSis()
    {
        return $this->belongsToMany(\App\Models\BacSi::class, 'bac_si_phong');
    }

    /**
     * Lịch làm việc sử dụng phòng này
     * MỞ RỘNG (Parent file: app/Models/Phong.php)
     */
    public function lichLamViecs()
    {
        return $this->hasMany(\App\Models\LichLamViec::class, 'phong_id');
    }

    /**
     * Lịch hẹn diễn ra trong phòng (qua bác sĩ)
     * MỞ RỘNG (Parent file: app/Models/Phong.php)
     */
    public function lichHens()
    {
        return $this->hasManyThrough(
            \App\Models\LichHen::class,
            \App\Models\LichLamViec::class,
            'phong_id',     // Foreign key on lich_lam_viecs
            'bac_si_id',    // Foreign key on lich_hens
            'id',           // Local key on phongs
            'bac_si_id'     // Local key on lich_lam_viecs
        );
    }

    // ==================== SCOPES ====================

    /**
     * Scope: Chỉ phòng sẵn sàng
     * MỞ RỘNG (Parent file: app/Models/Phong.php)
     */
    public function scopeAvailable($query)
    {
        return $query->where('trang_thai', 'Sẵn sàng');
    }

    /**
     * Scope: Phòng đang bảo trì
     * MỞ RỘNG (Parent file: app/Models/Phong.php)
     */
    public function scopeMaintenance($query)
    {
        return $query->where('trang_thai', 'Bảo trì');
    }

    /**
     * Scope: Phòng đang được sử dụng
     * MỞ RỘNG (Parent file: app/Models/Phong.php)
     */
    public function scopeInUse($query)
    {
        return $query->where('trang_thai', 'Đang sử dụng');
    }

    /**
     * Scope: Lọc theo loại phòng
     * MỞ RỘNG (Parent file: app/Models/Phong.php)
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('loai', $type);
    }

    // ==================== ACCESSORS & MUTATORS ====================

    /**
     * Get badge class theo trạng thái
     * MỞ RỘNG (Parent file: app/Models/Phong.php)
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->trang_thai) {
            'Sẵn sàng' => 'success',
            'Đang sử dụng' => 'primary',
            'Bảo trì' => 'warning',
            'Tạm ngưng' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Get icon theo trạng thái
     * MỞ RỘNG (Parent file: app/Models/Phong.php)
     */
    public function getStatusIconAttribute(): string
    {
        return match($this->trang_thai) {
            'Sẵn sàng' => 'bi-check-circle',
            'Đang sử dụng' => 'bi-hourglass-split',
            'Bảo trì' => 'bi-tools',
            'Tạm ngưng' => 'bi-x-circle',
            default => 'bi-question-circle'
        };
    }

    // ==================== HELPER METHODS ====================

    /**
     * Kiểm tra phòng có đang sử dụng không (tại thời điểm)
     * MỞ RỘNG (Parent file: app/Models/Phong.php)
     */
    public function isOccupiedAt(\Carbon\Carbon $datetime): bool
    {
        $service = app(\App\Services\RoomAvailabilityService::class);
        $status = $service->getRoomStatus($this->id, $datetime);
        return $status === 'occupied';
    }

    /**
     * Lấy danh sách bác sĩ đang làm việc trong phòng vào ngày
     * MỞ RỘNG (Parent file: app/Models/Phong.php)
     */
    public function getDoctorsWorkingOn($dayOfWeek)
    {
        return $this->lichLamViecs()
            ->where('ngay_trong_tuan', $dayOfWeek)
            ->with('bacSi')
            ->get()
            ->pluck('bacSi')
            ->unique('id');
    }

    /**
     * Tính tỷ lệ sử dụng phòng trong ngày
     * MỞ RỘNG (Parent file: app/Models/Phong.php)
     */
    public function getUtilizationForDate(string $date): float
    {
        $service = app(\App\Services\RoomAvailabilityService::class);
        $stats = $service->getRoomUsageStats($this->id, $date);
        return $stats['utilization'] ?? 0;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoaiSieuAm extends Model
{
    protected $fillable = [
        'ten',
        'mo_ta',
        'gia_mac_dinh',
        'thoi_gian_uoc_tinh',
        'phong_id',
        'is_active',
    ];

    protected $casts = [
        'gia_mac_dinh' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================

    /**
     * Many-to-many: Loại siêu âm thuộc nhiều chuyên khoa
     */
    public function chuyenKhoas()
    {
        return $this->belongsToMany(ChuyenKhoa::class, 'chuyen_khoa_loai_sieu_am');
    }

    /**
     * Phòng thực hiện siêu âm
     */
    public function phong()
    {
        return $this->belongsTo(Phong::class);
    }

    /**
     * Danh sách siêu âm sử dụng loại này
     */
    public function sieuAms()
    {
        return $this->hasMany(SieuAm::class);
    }

    // ============================================
    // SCOPES
    // ============================================

    /**
     * Chỉ lấy các loại siêu âm đang hoạt động
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ============================================
    // ACCESSORS & HELPERS
    // ============================================

    /**
     * Format giá hiển thị
     */
    public function getGiaFormattedAttribute(): string
    {
        return number_format($this->gia_mac_dinh, 0, ',', '.') . 'đ';
    }

    /**
     * Format thời gian ước tính
     */
    public function getThoiGianTextAttribute(): string
    {
        return $this->thoi_gian_uoc_tinh . ' phút';
    }

    /**
     * Lấy tất cả loại siêu âm đang hoạt động
     */
    public static function getAllActive()
    {
        return self::where('is_active', true)
            ->orderBy('ten')
            ->get();
    }
}

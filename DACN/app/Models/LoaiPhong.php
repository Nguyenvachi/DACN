<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model LoaiPhong - Quản lý loại phòng
 */
class LoaiPhong extends Model
{
    protected $fillable = [
        'ten',
        'slug',
        'mo_ta',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Các phòng thuộc loại này
     */
    public function phongs()
    {
        return $this->hasMany(\App\Models\Phong::class, 'loai_phong_id');
    }
}

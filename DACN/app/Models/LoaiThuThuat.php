<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoaiThuThuat extends Model
{
    protected $fillable = [
        'ten',
        'mo_ta',
        'gia_tien',
        'thoi_gian',
        'hoat_dong',
    ];

    protected $casts = [
        'gia_tien' => 'decimal:2',
        'hoat_dong' => 'boolean',
    ];

    /**
     * Các chỉ định thủ thuật thuộc loại này
     */
    public function thuThuats()
    {
        return $this->hasMany(ThuThuat::class, 'loai_thu_thuat_id');
    }
}

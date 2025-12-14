<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoaiSieuAm extends Model
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
     * Các chỉ định siêu âm thuộc loại này
     */
    public function sieuAms()
    {
        return $this->hasMany(SieuAm::class, 'loai_sieu_am_id');
    }
}

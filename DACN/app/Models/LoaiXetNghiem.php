<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoaiXetNghiem extends Model
{
    protected $fillable = [
        'ten',
        'ma',
        'mo_ta',
        'thoi_gian_uoc_tinh',
        'gia',
        'phong_id',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'gia' => 'decimal:2',
    ];

    public function chuyenKhoas()
    {
        return $this->belongsToMany(ChuyenKhoa::class, 'chuyen_khoa_loai_xet_nghiem');
    }

    public function phong()
    {
        return $this->belongsTo(Phong::class);
    }

    public function xetNghiems()
    {
        return $this->hasMany(XetNghiem::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoaiXQuang extends Model
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
        return $this->belongsToMany(ChuyenKhoa::class, 'chuyen_khoa_loai_x_quang');
    }

    public function phong()
    {
        return $this->belongsTo(Phong::class);
    }

    public function xQuangs()
    {
        return $this->hasMany(XQuang::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}

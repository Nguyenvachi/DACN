<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoaiNoiSoi extends Model
{
    protected $table = 'loai_noi_sois';

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
        return $this->belongsToMany(ChuyenKhoa::class, 'chuyen_khoa_loai_noi_soi');
    }

    public function phong()
    {
        return $this->belongsTo(Phong::class);
    }

    public function noiSois()
    {
        return $this->hasMany(NoiSoi::class, 'loai_noi_soi_id');
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}

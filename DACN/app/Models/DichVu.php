<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DichVu extends Model
{
    use HasFactory;

    protected $fillable = [
        'ten_dich_vu',
        'mo_ta',
        'gia',
        'thoi_gian_uoc_tinh',
    ];

    // THÊM RELATIONSHIP NÀY
    public function lichHens()
    {
        return $this->hasMany(LichHen::class, 'dich_vu_id');
    }

    // Quan hệ many-to-many với chuyên khoa
    public function chuyenKhoas()
    {
        return $this->belongsToMany(ChuyenKhoa::class, 'chuyen_khoa_dich_vu');
    }
}

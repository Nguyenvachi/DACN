<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class DichVu extends Model
{
    use HasFactory;

    protected $fillable = [
        'ten_dich_vu',
        'loai',
        'chuyen_khoa_id',
        'mo_ta',
        'gia_tien',
        'gia',
        'thoi_gian',
        'thoi_gian_uoc_tinh',
        'hoat_dong',
    ];

    protected $casts = [
        'gia_tien' => 'decimal:2',
        'hoat_dong' => 'boolean',
    ];

    // Relationship với chuyên khoa
    public function chuyenKhoa()
    {
        return $this->belongsTo(ChuyenKhoa::class);
    }

    // Relationship với lịch hẹn
    public function lichHens()
    {
        return $this->hasMany(LichHen::class, 'dich_vu_id');
    }
}

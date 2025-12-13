<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DichVu extends Model
{
    use HasFactory;

    protected $fillable = [
        'ten_dich_vu',
        'loai',
        'chuyen_khoa_id',
        'mo_ta',
        'gia_tien',
        'thoi_gian',
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

    // THÊM RELATIONSHIP NÀY
    public function lichHens()
    {
        return $this->hasMany(LichHen::class, 'dich_vu_id');
    }
}

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
        'mo_ta',
        'gia',
        'thoi_gian_uoc_tinh',
        'hoat_dong',
    ];

    protected $casts = [
        'gia' => 'decimal:2',
        'hoat_dong' => 'boolean',
    ];

    // THÊM RELATIONSHIP NÀY
    public function lichHens()
    {
        return $this->hasMany(LichHen::class, 'dich_vu_id');
    }

    // Relationship với dịch vụ nâng cao trong bệnh án
    public function benhAnDichVuNangCao()
    {
        return $this->hasMany(BenhAnDichVuNangCao::class);
    }
}

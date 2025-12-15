<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LichLamViec extends Model
{
    use HasFactory;

    protected $fillable = [
        'bac_si_id',
        'phong_id',
        'ngay_trong_tuan',
        'thangs',                 // Danh sách tháng áp dụng (1-12): "1,2,3" hoặc NULL (tất cả)
        'thoi_gian_bat_dau',
        'thoi_gian_ket_thuc',
    ];

    // THÊM relationship
    public function bacSi()
    {
        return $this->belongsTo(BacSi::class, 'bac_si_id');
    }

    public function phong()
    {
        return $this->belongsTo(\App\Models\Phong::class, 'phong_id');
    }
}

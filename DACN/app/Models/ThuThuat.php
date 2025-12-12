<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThuThuat extends Model
{
    use HasFactory;

    protected $fillable = [
        'benh_an_id',
        'bac_si_id',
        'ten_thu_thuat',
        'ngay_chi_dinh',
        'ngay_thuc_hien',
        'trang_thai',
        'chi_tiet_truoc_thu_thuat',
        'mo_ta_quy_trinh',
        'ket_qua',
        'bien_chung',
        'xu_tri',
        'gia_tien',
        'trang_thai_thanh_toan',
        'ghi_chu',
    ];

    protected $casts = [
        'ngay_chi_dinh' => 'date',
        'ngay_thuc_hien' => 'date',
        'gia_tien' => 'decimal:2',
    ];

    // Relationships
    public function benhAn()
    {
        return $this->belongsTo(BenhAn::class);
    }

    public function bacSi()
    {
        return $this->belongsTo(BacSi::class);
    }
}

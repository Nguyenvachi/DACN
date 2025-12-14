<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SieuAm extends Model
{
    use HasFactory;

    protected $fillable = [
        'benh_an_id',
        'bac_si_id',
        'loai_sieu_am',
        'ngay_chi_dinh',
        'ngay_thuc_hien',
        'trang_thai',
        'ket_qua',
        'nhan_xet',
        'hinh_anh',
        'tuoi_thai_tuan',
        'tuoi_thai_ngay',
        'can_nang_uoc_tinh',
        'chieu_dai_dau_mong',
        'duong_kinh_hai_dinh',
        'chu_vi_bung',
        'chieu_dai_xuong_dui',
        'luong_nuoc_oi',
        'gia_tien',
        'trang_thai_thanh_toan',
        'ghi_chu',
    ];

    protected $casts = [
        'ngay_chi_dinh' => 'date',
        'ngay_thuc_hien' => 'date',
        'hinh_anh' => 'array',
        'gia_tien' => 'decimal:2',
        'can_nang_uoc_tinh' => 'decimal:2',
        'chieu_dai_dau_mong' => 'decimal:2',
        'duong_kinh_hai_dinh' => 'decimal:2',
        'chu_vi_bung' => 'decimal:2',
        'chieu_dai_xuong_dui' => 'decimal:2',
        'luong_nuoc_oi' => 'decimal:2',
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

    public function bacSiThucHien()
    {
        return $this->belongsTo(BacSi::class, 'bac_si_id');
    }

    public function loaiSieuAm()
    {
        return $this->belongsTo(LoaiSieuAm::class, 'loai_sieu_am');
    }
}

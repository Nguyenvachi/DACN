<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XQuang extends Model
{
    use HasFactory;

    protected $table = 'x_quang';

    protected $fillable = [
        'benh_an_id',
        'dich_vu_id',
        'loai_x_quang',
        'vi_tri',
        'ngay_chi_dinh',
        'ngay_chup',
        'bac_si_chi_dinh_id',
        'bac_si_doc_ket_qua_id',
        'chi_dinh',
        'trang_thai',
        'ky_thuat',
        'mo_ta_hinh_anh',
        'tim_mach',
        'phoi',
        'xuong_khop',
        'co_quan_khac',
        'chan_doan',
        'hinh_anh',
        'ket_luan',
        'de_nghi',
        'ghi_chu',
    ];

    protected $casts = [
        'ngay_chi_dinh' => 'date',
        'ngay_chup' => 'date',
        'hinh_anh' => 'array',
    ];

    // Quan hệ
    public function benhAn()
    {
        return $this->belongsTo(BenhAn::class);
    }

    public function dichVu()
    {
        return $this->belongsTo(DichVu::class);
    }

    public function bacSiChiDinh()
    {
        return $this->belongsTo(BacSi::class, 'bac_si_chi_dinh_id');
    }

    public function bacSiDocKetQua()
    {
        return $this->belongsTo(BacSi::class, 'bac_si_doc_ket_qua_id');
    }

    // Helper methods
    public function getTrangThaiTextAttribute()
    {
        return match ($this->trang_thai) {
            'Chờ chụp' => 'Chờ chụp',
            'Đã chụp' => 'Đã chụp',
            'Đã có kết quả' => 'Đã có kết quả',
            'Đã hủy' => 'Đã hủy',
            default => $this->trang_thai,
        };
    }

    public function getTrangThaiBadgeAttribute()
    {
        return match ($this->trang_thai) {
            'Chờ chụp' => 'warning',
            'Đã chụp' => 'info',
            'Đã có kết quả' => 'success',
            'Đã hủy' => 'secondary',
            default => 'secondary',
        };
    }
}

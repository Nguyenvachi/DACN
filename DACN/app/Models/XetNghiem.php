<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XetNghiem extends Model
{
    protected $table = 'xet_nghiem';

    protected $fillable = [
        'benh_an_id',
        'dich_vu_id',
        'loai_xet_nghiem',
        'ten_xet_nghiem',
        'ngay_chi_dinh',
        'ngay_lay_mau',
        'ngay_tra_ket_qua',
        'bac_si_chi_dinh_id',
        'chi_dinh',
        'trang_thai',
        'can_nhin_an',
        'chuan_bi',
        'chi_so',
        'nhan_xet',
        'ket_luan',
        'file_ket_qua',
        'ghi_chu',
        'gia_tien',
    ];

    protected $casts = [
        'ngay_chi_dinh' => 'date',
        'ngay_lay_mau' => 'date',
        'ngay_tra_ket_qua' => 'date',
        'can_nhin_an' => 'boolean',
        'chi_so' => 'array',
        'file_ket_qua' => 'array',
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

    // Helper methods
    public function getTrangThaiTextAttribute()
    {
        return match ($this->trang_thai) {
            'Chờ lấy mẫu' => 'Chờ lấy mẫu',
            'Đã lấy mẫu' => 'Đã lấy mẫu',
            'Đang xét nghiệm' => 'Đang xét nghiệm',
            'Có kết quả' => 'Có kết quả',
            'Đã hủy' => 'Đã hủy',
            default => $this->trang_thai,
        };
    }

    public function getTrangThaiBadgeAttribute()
    {
        return match ($this->trang_thai) {
            'Chờ lấy mẫu' => 'warning',
            'Đã lấy mẫu' => 'info',
            'Đang xét nghiệm' => 'primary',
            'Có kết quả' => 'success',
            'Đã hủy' => 'secondary',
            default => 'secondary',
        };
    }
}

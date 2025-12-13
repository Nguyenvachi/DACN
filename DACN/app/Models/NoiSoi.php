<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoiSoi extends Model
{
    use HasFactory;

    protected $table = 'noi_soi';

    protected $fillable = [
        'benh_an_id',
        'dich_vu_id',
        'loai_noi_soi',
        'ngay_chi_dinh',
        'ngay_thuc_hien',
        'bac_si_chi_dinh_id',
        'bac_si_thuc_hien_id',
        'chi_dinh',
        'chuan_bi',
        'trang_thai',
        'mo_ta_hinh_anh',
        'ton_thuong',
        'chan_doan',
        'sinh_thiet',
        'hinh_anh',
        'xu_tri',
        'bien_chung',
        'ket_luan',
        'de_nghi',
        'ghi_chu',
    ];

    protected $casts = [
        'ngay_chi_dinh' => 'date',
        'ngay_thuc_hien' => 'date',
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

    public function bacSiThucHien()
    {
        return $this->belongsTo(BacSi::class, 'bac_si_thuc_hien_id');
    }

    // Helper methods
    public function getTrangThaiTextAttribute()
    {
        return match ($this->trang_thai) {
            'Chờ thực hiện' => 'Chờ thực hiện',
            'Đang thực hiện' => 'Đang thực hiện',
            'Hoàn thành' => 'Hoàn thành',
            'Đã hủy' => 'Đã hủy',
            default => $this->trang_thai,
        };
    }

    public function getTrangThaiBadgeAttribute()
    {
        return match ($this->trang_thai) {
            'Chờ thực hiện' => 'warning',
            'Đang thực hiện' => 'info',
            'Hoàn thành' => 'success',
            'Đã hủy' => 'secondary',
            default => 'secondary',
        };
    }
}

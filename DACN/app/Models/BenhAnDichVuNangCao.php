<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BenhAnDichVuNangCao extends Model
{
    use HasFactory;

    protected $table = 'benh_an_dich_vu_nang_cao';

    protected $fillable = [
        'benh_an_id',
        'dich_vu_id',
        'gia_tai_thoi_diem',
        'trang_thai',
        'ghi_chu',
        'ket_qua',
        'thoi_gian_thuc_hien',
        'nguoi_thuc_hien_id',
    ];

    protected $casts = [
        'gia_tai_thoi_diem' => 'decimal:2',
        'thoi_gian_thuc_hien' => 'datetime',
    ];

    // Relationships
    public function benhAn()
    {
        return $this->belongsTo(BenhAn::class, 'benh_an_id');
    }

    public function dichVu()
    {
        return $this->belongsTo(DichVu::class, 'dich_vu_id');
    }

    public function nguoiThucHien()
    {
        return $this->belongsTo(User::class, 'nguoi_thuc_hien_id');
    }
}

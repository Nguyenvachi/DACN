<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoaDonChiTiet extends Model
{
    use HasFactory;

    protected $fillable = [
        'hoa_don_id',
        'loai_dich_vu',
        'dich_vu_id',
        'ten_dich_vu',
        'mo_ta',
        'so_luong',
        'don_gia',
        'thanh_tien',
    ];

    protected $casts = [
        'so_luong' => 'integer',
        'don_gia' => 'decimal:2',
        'thanh_tien' => 'decimal:2',
    ];

    // Boot event để tự động tính thành tiền
    protected static function booted()
    {
        static::saving(function ($chiTiet) {
            $chiTiet->thanh_tien = $chiTiet->so_luong * $chiTiet->don_gia;
        });
    }

    // Relationships
    public function hoaDon()
    {
        return $this->belongsTo(HoaDon::class);
    }

    // Polymorphic relationship (tùy vào loai_dich_vu)
    public function dichVu()
    {
        switch ($this->loai_dich_vu) {
            case 'thuoc':
                return $this->belongsTo(Thuoc::class, 'dich_vu_id');
            case 'noi_soi':
                return $this->belongsTo(NoiSoi::class, 'dich_vu_id');
            case 'x_quang':
                return $this->belongsTo(XQuang::class, 'dich_vu_id');
            case 'xet_nghiem':
                return $this->belongsTo(XetNghiem::class, 'dich_vu_id');
            case 'thu_thuat':
                return $this->belongsTo(ThuThuat::class, 'dich_vu_id');
            default:
                return null;
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonHangItem extends Model
{
    use HasFactory;

    protected $fillable = ['don_hang_id', 'thuoc_id', 'so_luong', 'don_gia', 'thanh_tien'];

    protected static function booted()
    {
        static::saving(function ($item) {
            $item->thanh_tien = $item->so_luong * $item->don_gia;
        });

        static::saved(function ($item) {
            $item->donHang->recalculateTotal();
        });

        static::deleted(function ($item) {
            $item->donHang->recalculateTotal();
        });
    }

    public function donHang()
    {
        return $this->belongsTo(DonHang::class);
    }

    public function thuoc()
    {
        return $this->belongsTo(Thuoc::class);
    }
}

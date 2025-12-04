<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonHang extends Model
{
    use HasFactory;

    protected $fillable = [
        'ma_don_hang', 'user_id', 'coupon_id', 'tong_tien', 'giam_gia', 'thanh_toan',
        'trang_thai', 'trang_thai_thanh_toan', 'dia_chi_giao', 'sdt_nguoi_nhan', 'ghi_chu',
        'ngay_dat', 'ngay_giao_du_kien'
    ];

    protected $casts = [
        'ngay_dat' => 'datetime',
        'ngay_giao_du_kien' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($donHang) {
            if (!$donHang->ma_don_hang) {
                $donHang->ma_don_hang = 'DH-' . now()->format('YmdHis') . '-' . rand(1000, 9999);
            }
        });

        static::saving(function ($donHang) {
            if ($donHang->coupon_id && $donHang->isDirty(['tong_tien', 'coupon_id'])) {
                $coupon = Coupon::find($donHang->coupon_id);
                if ($coupon && $coupon->kiemTraHopLe($donHang->tong_tien)) {
                    $donHang->giam_gia = $coupon->tinhGiamGia($donHang->tong_tien);
                }
            }
            $donHang->thanh_toan = max(0, $donHang->tong_tien - $donHang->giam_gia);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function items()
    {
        return $this->hasMany(DonHangItem::class);
    }

    public function recalculateTotal(): void
    {
        $this->tong_tien = $this->items()->sum('thanh_tien');
        $this->save();
    }
}

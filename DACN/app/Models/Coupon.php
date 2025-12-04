<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'ma_giam_gia', 'ten', 'mo_ta', 'loai', 'gia_tri', 'giam_toi_da', 'don_toi_thieu',
        'ngay_bat_dau', 'ngay_ket_thuc', 'so_lan_su_dung_toi_da', 'so_lan_da_su_dung', 'kich_hoat'
    ];

    protected $casts = [
        'ngay_bat_dau' => 'date',
        'ngay_ket_thuc' => 'date',
        'kich_hoat' => 'boolean',
    ];

    public function donHangs()
    {
        return $this->hasMany(DonHang::class);
    }

    public function kiemTraHopLe(?float $tongTien = null): bool
    {
        $now = Carbon::now();
        if (!$this->kich_hoat) return false;
        if ($now->lt($this->ngay_bat_dau) || $now->gt($this->ngay_ket_thuc)) return false;
        if ($this->so_lan_su_dung_toi_da && $this->so_lan_da_su_dung >= $this->so_lan_su_dung_toi_da) return false;
        if ($tongTien !== null && $this->don_toi_thieu && $tongTien < $this->don_toi_thieu) return false;
        return true;
    }

    public function tinhGiamGia(float $tongTien): float
    {
        if (!$this->kiemTraHopLe($tongTien)) return 0;
        if ($this->loai === 'tien_mat') return min($this->gia_tri, $tongTien);
        $giamGia = ($tongTien * $this->gia_tri) / 100;
        if ($this->giam_toi_da) $giamGia = min($giamGia, $this->giam_toi_da);
        return min($giamGia, $tongTien);
    }

    public function tangSuDung(): void
    {
        $this->increment('so_lan_da_su_dung');
    }
}

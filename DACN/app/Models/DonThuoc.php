<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonThuoc extends Model
{
    protected $fillable = [
        'benh_an_id',
        'user_id',
        'bac_si_id',
        'lich_hen_id',
        'ghi_chu',
        'ngay_cap_thuoc',
        'nguoi_cap_thuoc_id',
        'ghi_chu_cap_thuoc'
    ];

    protected $casts = [
        'ngay_cap_thuoc' => 'datetime',
    ];

    public function benhAn()
    {
        return $this->belongsTo(BenhAn::class);
    }
    public function items()
    {
        return $this->hasMany(DonThuocItem::class);
    }
    public function nguoiCapThuoc()
    {
        return $this->belongsTo(User::class, 'nguoi_cap_thuoc_id');
    }
    public function bacSi()
    {
        return $this->belongsTo(BacSi::class, 'bac_si_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

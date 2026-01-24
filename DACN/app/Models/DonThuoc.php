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
        'trang_thai',
        'ngay_cap_thuoc',
        'nguoi_cap_thuoc_id',
        'ghi_chu_cap_thuoc',
    ];

    protected $casts = [
        'ngay_cap_thuoc' => 'datetime',
    ];

    public const STATUS_DA_CAP_THUOC = 'da_cap_thuoc';

    public function benhAn(){ return $this->belongsTo(BenhAn::class); }
    public function items(){ return $this->hasMany(DonThuocItem::class); }

    /**
     * Nhân viên (User) đã cấp thuốc
     */
    public function nguoiCapThuoc(){ return $this->belongsTo(User::class, 'nguoi_cap_thuoc_id'); }

    /**
     * Scope: đơn thuốc chờ cấp
     */
    public function scopeChoCapThuoc($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('trang_thai')
              ->orWhere('trang_thai', '!=', self::STATUS_DA_CAP_THUOC);
        });
    }
}

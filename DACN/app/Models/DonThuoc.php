<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonThuoc extends Model
{
    protected $fillable = ['benh_an_id','user_id','bac_si_id','lich_hen_id','ghi_chu'];

    public function benhAn(){ return $this->belongsTo(BenhAn::class); }
    public function items(){ return $this->hasMany(DonThuocItem::class); }
}
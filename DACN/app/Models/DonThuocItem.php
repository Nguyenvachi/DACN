<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonThuocItem extends Model
{
    protected $fillable = ['don_thuoc_id','thuoc_id','so_luong','lieu_dung','cach_dung'];

    public function donThuoc(){ return $this->belongsTo(DonThuoc::class); }
    public function thuoc(){ return $this->belongsTo(Thuoc::class); }
}
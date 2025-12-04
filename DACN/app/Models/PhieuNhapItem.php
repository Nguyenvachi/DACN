<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhieuNhapItem extends Model
{
    protected $fillable = ['phieu_nhap_id','thuoc_id','ma_lo','han_su_dung','so_luong','don_gia','thanh_tien'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhieuXuatItem extends Model
{
    protected $fillable = ['phieu_xuat_id','thuoc_id','so_luong','don_gia','thanh_tien'];
}

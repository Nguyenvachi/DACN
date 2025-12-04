<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhieuNhap extends Model
{
    protected $fillable = ['ma_phieu','ngay_nhap','nha_cung_cap_id','user_id','tong_tien','ghi_chu'];
}

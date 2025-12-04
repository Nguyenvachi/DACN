<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhieuXuat extends Model
{
    protected $fillable = ['ma_phieu','ngay_xuat','user_id','doi_tuong','tong_tien','ghi_chu'];
}

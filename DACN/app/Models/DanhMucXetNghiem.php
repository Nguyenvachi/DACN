<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanhMucXetNghiem extends Model
{
    protected $table = 'danh_muc_xet_nghiem';

    protected $fillable = [
        'ten_xet_nghiem',
        'gia_tien',
        'ghi_chu',
    ];
}

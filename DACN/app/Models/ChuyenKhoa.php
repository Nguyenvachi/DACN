<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChuyenKhoa extends Model
{
    protected $fillable = ['ten','slug','mo_ta'];

    public function bacSis()
    {
        return $this->belongsToMany(\App\Models\BacSi::class, 'bac_si_chuyen_khoa');
    }

    // Quan hệ many-to-many với dịch vụ
    public function dichVus()
    {
        return $this->belongsToMany(\App\Models\DichVu::class, 'chuyen_khoa_dich_vu');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaLamViecNhanVien extends Model
{
    protected $fillable = ['nhan_vien_id','ngay','bat_dau','ket_thuc','ghi_chu'];

    public function nhanVien(){ return $this->belongsTo(NhanVien::class); }
}
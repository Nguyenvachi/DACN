<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NhanVien extends Model
{
    protected $fillable = [
        'user_id','ho_ten','chuc_vu','so_dien_thoai','email_cong_viec',
        'ngay_sinh','gioi_tinh','avatar_path','trang_thai'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function caLamViecs()
    {
        return $this->hasMany(CaLamViecNhanVien::class);
    }

    public function audits()
    {
        return $this->hasMany(NhanVienAudit::class);
    }
}

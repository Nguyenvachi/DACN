<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NhanVienAudit extends Model
{
    protected $fillable = [
        'nhan_vien_id',
        'user_id',
        'action',
        'old_data',
        'new_data',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    public function nhanVien()
    {
        return $this->belongsTo(NhanVien::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

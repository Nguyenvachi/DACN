<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FamilyMember extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'ho_ten',
        'quan_he',
        'ngay_sinh',
        'gioi_tinh',
        'so_dien_thoai',
        'email',
        'dia_chi',
        'nhom_mau',
        'chieu_cao',
        'can_nang',
        'tien_su_benh',
        'bhyt_ma_so',
        'bhyt_ngay_het_han',
        'avatar',
    ];

    protected $casts = [
        'ngay_sinh' => 'date',
        'bhyt_ngay_het_han' => 'date',
        'chieu_cao' => 'decimal:2',
        'can_nang' => 'decimal:2',
    ];

    // Relationship với User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

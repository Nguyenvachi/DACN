<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LichTaiKham extends Model
{
    use HasFactory;

    protected $fillable = [
        'benh_an_id',
        'bac_si_id',
        'benh_nhan_id',
        'ngay_hen',
        'gio_hen',
        'ly_do',
        'luu_y',
        'trang_thai',
        'ghi_chu',
        'ngay_xac_nhan',
        'ngay_kham_thuc_te',
    ];

    protected $casts = [
        'ngay_hen' => 'date',
        'ngay_xac_nhan' => 'datetime',
        'ngay_kham_thuc_te' => 'datetime',
    ];

    // Relationships
    public function benhAn()
    {
        return $this->belongsTo(BenhAn::class);
    }

    public function bacSi()
    {
        return $this->belongsTo(BacSi::class);
    }

    public function benhNhan()
    {
        return $this->belongsTo(User::class, 'benh_nhan_id');
    }
}

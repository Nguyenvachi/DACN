<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BenhAn extends Model
{
    protected $fillable = [
        'user_id',
        'bac_si_id',
        'lich_hen_id',
        'ngay_kham',
        'tieu_de',
        'trieu_chung',
        'chuan_doan',
        'dieu_tri',
        'ghi_chu',
    ];

    protected $casts = [
        'ngay_kham' => 'date',
    ];

    public function benhNhan()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function bacSi()
    {
        return $this->belongsTo(BacSi::class, 'bac_si_id');
    }
    public function lichHen()
    {
        return $this->belongsTo(LichHen::class, 'lich_hen_id');
    }
    public function files()
    {
        return $this->hasMany(BenhAnFile::class);
    }

    // bổ sung quan hệ kê đơn và xét nghiệm
    public function donThuocs() {
        return $this->hasMany(\App\Models\DonThuoc::class);
    }

    public function xetNghiems() {
        return $this->hasMany(\App\Models\XetNghiem::class);
    }

    // bổ sung quan hệ audit trail
    public function audits() {
        return $this->hasMany(\App\Models\BenhAnAudit::class)->orderByDesc('created_at');
    }
}

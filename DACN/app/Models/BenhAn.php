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

    // Alias cho benhNhan để thuận tiện
    public function user()
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

    // Lấy dịch vụ qua lịch hẹn
    public function dichVu()
    {
        return $this->hasOneThrough(
            DichVu::class,
            LichHen::class,
            'id',           // Foreign key on lich_hens table
            'id',           // Foreign key on dich_vus table
            'lich_hen_id',  // Local key on benh_ans table
            'dich_vu_id'    // Local key on lich_hens table
        );
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

    // THÊM: Relationship với Siêu âm (File con: app/Models/SieuAm.php)
    public function sieuAms() {
        return $this->hasMany(\App\Models\SieuAm::class);
    }

    // THÊM: Relationship với X-Quang (File con: app/Models/XQuang.php)
    public function xQuangs() {
        return $this->hasMany(\App\Models\XQuang::class);
    }

    // THÊM: Relationship với Theo dõi thai kỳ (File con: app/Models/TheoDoiThaiKy.php)
    public function theoDoiThaiKys() {
        return $this->hasMany(\App\Models\TheoDoiThaiKy::class);
    }

    // THÊM: Relationship với Tái khám (File con: app/Models/TaiKham.php)
    public function taiKhams() {
        return $this->hasMany(\App\Models\TaiKham::class);
    }

    // bổ sung quan hệ audit trail
    public function audits() {
        return $this->hasMany(\App\Models\BenhAnAudit::class)->orderByDesc('created_at');
    }
}

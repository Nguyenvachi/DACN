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
        'trang_thai',
        'ngay_hen_tai_kham',
        'ly_do_tai_kham',
        // Vital signs
        'huyet_ap',
        'nhip_tim',
        'nhiet_do',
        'nhip_tho',
        'can_nang',
        'chieu_cao',
        'bmi',
        'spo2',
    ];

    protected $casts = [
        'ngay_kham' => 'date',
        'ngay_hen_tai_kham' => 'date',
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
    public function donThuocs()
    {
        return $this->hasMany(\App\Models\DonThuoc::class);
    }

    public function xetNghiems()
    {
        return $this->hasMany(\App\Models\XetNghiem::class);
    }

    // bổ sung quan hệ audit trail
    public function audits()
    {
        return $this->hasMany(\App\Models\BenhAnAudit::class)->orderByDesc('created_at');
    }

    // ✅ Quan hệ với siêu âm
    public function sieuAms()
    {
        return $this->hasMany(SieuAm::class);
    }

    // ✅ Quan hệ với thủ thuật
    public function thuThuats()
    {
        return $this->hasMany(ThuThuat::class);
    }

    // ✅ Quan hệ với lịch tái khám
    public function lichTaiKhams()
    {
        return $this->hasMany(LichTaiKham::class);
    }

    // ✅ Quan hệ với nội soi
    public function noiSois()
    {
        return $this->hasMany(NoiSoi::class);
    }

    // ✅ Quan hệ với X-quang
    public function xQuangs()
    {
        return $this->hasMany(XQuang::class);
    }

    public function theoDoiThaiKy()
    {
        return $this->hasMany(TheoDoiThaiKy::class);
    }

    // ✅ Helper: Tính tổng tiền tất cả dịch vụ đã chỉ định
    public function tongTienDichVu()
    {
        $tongXetNghiem = $this->xetNghiems()->sum('gia_tien') ?? 0;
        $tongSieuAm = $this->sieuAms()->sum('gia_tien') ?? 0;
        $tongThuThuat = $this->thuThuats()->sum('gia_tien') ?? 0;
        $tongDichVuNangCao = $this->dichVuNangCao()->sum('gia_tien') ?? 0;
        $tongTheoDoiThaiKy = $this->theoDoiThaiKy()->sum('gia_tien') ?? 0;

        return $tongXetNghiem + $tongSieuAm + $tongThuThuat + $tongDichVuNangCao + $tongTheoDoiThaiKy;
    }
}

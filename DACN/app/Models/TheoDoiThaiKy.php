<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TheoDoiThaiKy extends Model
{
    use HasFactory;

    protected $table = 'theo_doi_thai_ky';

    protected $fillable = [
        'user_id',
        'bac_si_id',
        'ngay_kinh_cuoi',
        'ngay_du_sinh',
        'so_lan_mang_thai',
        'so_lan_sinh',
        'so_con_song',
        'loai_thai',
        'nhom_mau',
        'rh',
        'can_nang_truoc_mang_thai',
        'chieu_cao',
        'bmi_truoc_mang_thai',
        'tien_su_san_khoa',
        'tien_su_benh_ly',
        'di_ung',
        'trang_thai',
        'ngay_ket_thuc',
        'ket_qua_thai_ky',
        'ghi_chu',
    ];

    protected $casts = [
        'ngay_kinh_cuoi' => 'date',
        'ngay_du_sinh' => 'date',
        'ngay_ket_thuc' => 'date',
        'can_nang_truoc_mang_thai' => 'decimal:2',
        'chieu_cao' => 'decimal:2',
        'bmi_truoc_mang_thai' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bacSi()
    {
        return $this->belongsTo(BacSi::class);
    }

    public function lichKhamThai()
    {
        return $this->hasMany(LichKhamThai::class);
    }

    public function tiemChung()
    {
        return $this->hasMany(TiemChungMeBau::class);
    }

    public function theoDoiHauSan()
    {
        return $this->hasMany(TheoDoiHauSan::class);
    }

    // Helper: Tính tuổi thai hiện tại
    public function tuoiThaiHienTai()
    {
        if (!$this->ngay_kinh_cuoi) {
            return null;
        }

        $ngayKinhCuoi = Carbon::parse($this->ngay_kinh_cuoi);
        $soNgay = $ngayKinhCuoi->diffInDays(now());
        $soTuan = floor($soNgay / 7);
        $soNgayLe = $soNgay % 7;

        return [
            'tuan' => $soTuan,
            'ngay' => $soNgayLe,
            'tong_ngay' => $soNgay,
        ];
    }

    // Helper: Tính ngày dự sinh từ ngày kinh cuối
    public static function tinhNgayDuSinh($ngayKinhCuoi)
    {
        return Carbon::parse($ngayKinhCuoi)->addDays(280);
    }

    // Helper: Số ngày còn lại đến ngày dự sinh
    public function soNgayConLai()
    {
        if (!$this->ngay_du_sinh) {
            return null;
        }

        return Carbon::parse($this->ngay_du_sinh)->diffInDays(now(), false);
    }
}

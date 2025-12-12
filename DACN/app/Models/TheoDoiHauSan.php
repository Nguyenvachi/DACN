<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TheoDoiHauSan extends Model
{
    use HasFactory;

    protected $table = 'theo_doi_hau_san';

    protected $fillable = [
        'theo_doi_thai_ky_id',
        'bac_si_id',
        'ngay_sinh',
        'phuong_phap_sinh',
        'dieu_kien_sinh',
        'can_nang_tre',
        'chieu_dai_tre',
        'vong_dau_tre',
        'diem_apgar_1',
        'diem_apgar_5',
        'gioi_tinh_tre',
        'ngay_kham',
        'ngay_sau_sinh',
        'can_nang_me',
        'huyet_ap_tam_thu',
        'huyet_ap_tam_truong',
        'nhiet_do',
        'tinh_trang_tu_cung',
        'tinh_trang_vu',
        'lochia',
        'tinh_trang_thuong_tich',
        'trieu_chung',
        'tam_trang',
        'cho_con_bu',
        'danh_gia',
        'tu_van',
        'chi_dinh',
        'hen_kham_lai',
        'ghi_chu',
    ];

    protected $casts = [
        'ngay_sinh' => 'date',
        'ngay_kham' => 'date',
        'hen_kham_lai' => 'date',
        'can_nang_tre' => 'decimal:2',
        'chieu_dai_tre' => 'decimal:2',
        'vong_dau_tre' => 'decimal:2',
        'can_nang_me' => 'decimal:2',
        'huyet_ap_tam_thu' => 'decimal:2',
        'huyet_ap_tam_truong' => 'decimal:2',
        'nhiet_do' => 'decimal:2',
    ];

    // Relationships
    public function theoDoiThaiKy()
    {
        return $this->belongsTo(TheoDoiThaiKy::class);
    }

    public function bacSi()
    {
        return $this->belongsTo(BacSi::class);
    }

    // Lịch khám hậu sản khuyến cáo
    public static function lichKhamKhuyenCao()
    {
        return [
            ['ngay_sau_sinh' => 3, 'muc_dich' => 'Khám sơ sinh, theo dõi mẹ sau sinh'],
            ['ngay_sau_sinh' => 7, 'muc_dich' => 'Kiểm tra vết mổ/rạch, theo dõi co hồi tử cung'],
            ['ngay_sau_sinh' => 42, 'muc_dich' => 'Khám hậu sản 6 tuần, tư vấn kế hoạch hóa gia đình'],
        ];
    }
}

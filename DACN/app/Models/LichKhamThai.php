<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LichKhamThai extends Model
{
    use HasFactory;

    protected $table = 'lich_kham_thai';

    protected $fillable = [
        'theo_doi_thai_ky_id',
        'bac_si_id',
        'ngay_kham',
        'tuan_thai',
        'ngay_thai',
        'can_nang',
        'huyet_ap_tam_thu',
        'huyet_ap_tam_truong',
        'nhiet_do',
        'nhip_tim_me',
        'chieu_cao_tu_cung',
        'vong_bung',
        'nhip_tim_thai',
        'vi_tri_thai',
        'trieu_chung',
        'kham_lam_sang',
        'chi_dinh',
        'danh_gia',
        'tu_van',
        'hen_kham_lai',
        'ghi_chu',
    ];

    protected $casts = [
        'ngay_kham' => 'date',
        'hen_kham_lai' => 'date',
        'can_nang' => 'decimal:2',
        'huyet_ap_tam_thu' => 'decimal:2',
        'huyet_ap_tam_truong' => 'decimal:2',
        'nhiet_do' => 'decimal:2',
        'chieu_cao_tu_cung' => 'decimal:2',
        'vong_bung' => 'decimal:2',
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
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiemChungMeBau extends Model
{
    use HasFactory;

    protected $table = 'tiem_chung_me_bau';

    protected $fillable = [
        'theo_doi_thai_ky_id',
        'bac_si_id',
        'ten_vaccine',
        'tuan_thai_du_kien',
        'ngay_du_kien',
        'ngay_tiem',
        'lo_vaccine',
        'noi_tiem',
        'nguoi_tiem',
        'mui_so',
        'tong_so_mui',
        'hen_mui_tiep',
        'phan_ung_sau_tiem',
        'muc_do_phan_ung',
        'trang_thai',
        'ly_do_khong_tiem',
        'ghi_chu',
    ];

    protected $casts = [
        'ngay_du_kien' => 'date',
        'ngay_tiem' => 'date',
        'hen_mui_tiep' => 'date',
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

    // Danh sách vaccine khuyến cáo cho mẹ bầu
    public static function danhSachVaccineKhuyenCao()
    {
        return [
            ['ten' => 'Uốn ván - Bạch hầu - Ho gà (Tdap)', 'tuan_thai' => 27, 'so_mui' => 1],
            ['ten' => 'Cúm (Influenza)', 'tuan_thai' => 12, 'so_mui' => 1],
            ['ten' => 'Viêm gan B', 'tuan_thai' => 0, 'so_mui' => 3],
            ['ten' => 'COVID-19', 'tuan_thai' => 12, 'so_mui' => 2],
        ];
    }
}

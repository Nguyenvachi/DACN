<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThuocKho extends Model
{
    protected $fillable = [
        'thuoc_id','ma_lo','han_su_dung','so_luong','gia_nhap','gia_xuat','nha_cung_cap_id'
    ];

    /**
     * Model events
     */
    protected static function booted()
    {
        // Guard: Chặn tồn kho âm
        static::saving(function ($thuocKho) {
            if ($thuocKho->so_luong < 0) {
                throw new \Exception(
                    'Không thể lưu tồn kho âm. Thuốc ID: ' . $thuocKho->thuoc_id .
                    ', Lô: ' . ($thuocKho->ma_lo ?? 'N/A') .
                    ', Số lượng: ' . $thuocKho->so_luong
                );
            }
        });
    }

    // Quan hệ tới Thuoc (để eager load trong báo cáo, lots)
    public function thuoc()
    {
        return $this->belongsTo(\App\Models\Thuoc::class, 'thuoc_id');
    }

    // Quan hệ tới Nhà cung cấp (tham khảo nếu cần hiển thị)
    public function nhaCungCap()
    {
        return $this->belongsTo(\App\Models\NhaCungCap::class, 'nha_cung_cap_id');
    }
}

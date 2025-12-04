<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thuoc extends Model
{
    protected $fillable = ['ten','hoat_chat','ham_luong','don_vi','gia_tham_khao','ton_toi_thieu'];

    // Bổ sung: quan hệ kho để thống kê tồn
    public function kho()
    {
        return $this->hasMany(\App\Models\ThuocKho::class, 'thuoc_id');
    }

    /**
     * Phiếu nhập items chứa thuốc này
     */
    public function phieuNhapItems()
    {
        return $this->hasMany(PhieuNhapItem::class, 'thuoc_id');
    }

    /**
     * Phiếu xuất items chứa thuốc này
     */
    public function phieuXuatItems()
    {
        return $this->hasMany(PhieuXuatItem::class, 'thuoc_id');
    }

    /**
     * Danh sách NCC cung cấp thuốc này
     */
    public function nhaCungCaps()
    {
        return $this->belongsToMany(NhaCungCap::class, 'nha_cung_cap_thuoc')
            ->withPivot('gia_nhap_mac_dinh')
            ->withTimestamps();
    }

    /**
     * Cập nhật giá xuất cho tất cả lô thuốc
     * @param float $giaXuatMoi
     * @param string|null $maLo - Nếu null thì cập nhật tất cả lô
     */
    public function updateGiaXuat(float $giaXuatMoi, ?string $maLo = null): int
    {
        $query = $this->kho();

        if ($maLo !== null) {
            $query->where('ma_lo', $maLo);
        }

        return $query->update(['gia_xuat' => $giaXuatMoi]);
    }

    /**
     * Tính tổng tồn kho hiện tại
     */
    public function tongTonKho(): int
    {
        return $this->kho()->sum('so_luong') ?? 0;
    }

    /**
     * Kiểm tra có cảnh báo giảm tồn không
     */
    public function canhBaoGiamTon(): bool
    {
        if (!$this->ton_toi_thieu) {
            return false;
        }

        return $this->tongTonKho() < $this->ton_toi_thieu;
    }
}

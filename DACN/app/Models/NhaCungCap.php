<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NhaCungCap extends Model
{
    protected $fillable = ['ten','dia_chi','so_dien_thoai','email','ghi_chu'];

    /**
     * Danh sách thuốc mà NCC này cung cấp (many-to-many)
     */
    public function thuocs()
    {
        return $this->belongsToMany(Thuoc::class, 'nha_cung_cap_thuoc')
            ->withPivot('gia_nhap_mac_dinh')
            ->withTimestamps();
    }

    /**
     * Phiếu nhập từ NCC này
     */
    public function phieuNhaps()
    {
        return $this->hasMany(PhieuNhap::class, 'nha_cung_cap_id');
    }

    /**
     * Kiểm tra NCC có cung cấp thuốc này không
     */
    public function cungCapThuoc(int $thuocId): bool
    {
        return $this->thuocs()->where('thuoc_id', $thuocId)->exists();
    }
}

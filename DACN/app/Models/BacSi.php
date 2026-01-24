<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BacSi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'avatar',
        'ho_ten',
        'email',
        'chuyen_khoa',
        'so_dien_thoai',
        'dia_chi',
        'kinh_nghiem',
        'mo_ta',
        'trang_thai',
    ];

    protected static function booted()
    {
        static::created(function ($bacSi) {
            if ($bacSi->user && !$bacSi->user->isDoctor()) {
                $bacSi->user->forceFill(['role' => 'doctor'])->save();
            }
        });
    }

    public function lichLamViecs()
    {
        return $this->hasMany(\App\Models\LichLamViec::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lichHens()
    {
        return $this->hasMany(\App\Models\LichHen::class);
    }

    public function lichNghis()
    {
        return $this->hasMany(LichNghi::class, 'bac_si_id');
    }

    public function caDieuChinhs()
    {
        return $this->hasMany(CaDieuChinhBacSi::class, 'bac_si_id');
    }

    public function danhGias()
    {
        return $this->hasMany(DanhGia::class, 'bac_si_id');
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'bac_si_id');
    }

    public function xetNghiems()
    {
        return $this->hasMany(\App\Models\XetNghiem::class, 'bac_si_id');
    }

    // THÊM: Relationship với Siêu âm (File con: app/Models/SieuAm.php)
    // Alias cho sieuAmsChiDinh (backward compatibility)
    public function sieuAms()
    {
        return $this->hasMany(\App\Models\SieuAm::class, 'bac_si_chi_dinh_id');
    }

    /**
     * Siêu âm do bác sĩ chỉ định
     */
    public function sieuAmsChiDinh()
    {
        return $this->hasMany(\App\Models\SieuAm::class, 'bac_si_chi_dinh_id');
    }

    /**
     * Siêu âm do bác sĩ thực hiện (có thể khác bác sĩ chỉ định)
     */
    public function sieuAmsThucHien()
    {
        return $this->hasMany(\App\Models\SieuAm::class, 'bac_si_sieu_am_id');
    }

    // Thuộc 1 chuyên khoa (singular)
    public function chuyenKhoa()
    {
        return $this->belongsTo(\App\Models\ChuyenKhoa::class, 'chuyen_khoa_id');
    }

    // Thuộc nhiều chuyên khoa
    public function chuyenKhoas()
    {
        return $this->belongsToMany(\App\Models\ChuyenKhoa::class, 'bac_si_chuyen_khoa');
    }

    // Thuộc nhiều phòng
    public function phongs()
    {
        return $this->belongsToMany(\App\Models\Phong::class, 'bac_si_phong');
    }

    /**
     * Return a public URL for the doctor's avatar if available.
     * Falls back to the related user's avatar when present.
     */
    public function getAvatarUrlAttribute()
    {
        if (!empty($this->avatar)) {
            return asset('storage/' . ltrim($this->avatar, '/'));
        }

        if ($this->user && !empty($this->user->avatar)) {
            return asset('storage/' . ltrim($this->user->avatar, '/'));
        }

        return null;
    }
}

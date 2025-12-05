<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Parent file: app/Models/PatientProfile.php
 */
class PatientProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nhom_mau',
        'chieu_cao',
        'can_nang',
        'allergies',
        'tien_su_benh',
        'thuoc_dang_dung',
        'benh_man_tinh',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'avatar',
    ];

    protected $casts = [
        'allergies' => 'array', // Cast JSON to array
        'chieu_cao' => 'decimal:2',
        'can_nang' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Tính BMI
     */
    public function getBmiAttribute()
    {
        if ($this->chieu_cao && $this->can_nang) {
            $heightInMeters = $this->chieu_cao / 100;
            return round($this->can_nang / ($heightInMeters * $heightInMeters), 1);
        }
        return null;
    }

    /**
     * Đánh giá BMI
     */
    public function getBmiCategoryAttribute()
    {
        $bmi = $this->bmi;
        if (!$bmi) return null;

        if ($bmi < 18.5) return 'Thiếu cân';
        if ($bmi < 25) return 'Bình thường';
        if ($bmi < 30) return 'Thừa cân';
        return 'Béo phì';
    }
}

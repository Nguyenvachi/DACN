<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $unreadNotifications
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // admin, staff, doctor, patient
        'so_dien_thoai',
        'ngay_sinh',
        'gioi_tinh',
        'locked_at',
        'locked_until',
        'must_change_password',
        'last_login_at',
        'login_attempts',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'locked_at' => 'datetime',
        'locked_until' => 'datetime',
        'last_login_at' => 'datetime',
        'must_change_password' => 'boolean',
        'ngay_sinh' => 'date',
    ];

    // --- ROLE CHECK METHODS ---
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isDoctor(): bool
    {
        return $this->role === 'doctor';
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    public function isPatient(): bool
    {
        return $this->role === 'patient';
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    // --- CÁC RELATIONSHIP KHÁC GIỮ NGUYÊN ---
    public function bacSi()
    {
        return $this->hasOne(\App\Models\BacSi::class, 'user_id');
    }

    public function nhanVien()
    {
        return $this->hasOne(\App\Models\NhanVien::class, 'user_id');
    }

    public function loginAudits()
    {
        return $this->hasMany(\App\Models\LoginAudit::class);
    }

    public function patientProfile()
    {
        return $this->hasOne(\App\Models\PatientProfile::class);
    }

    public function notificationPreference()
    {
        return $this->hasOne(\App\Models\NotificationPreference::class);
    }

    public function danhGias()
    {
        return $this->hasMany(DanhGia::class);
    }

    public function conversationsAsBenhNhan()
    {
        return $this->hasMany(Conversation::class, 'benh_nhan_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function familyMembers()
    {
        return $this->hasMany(FamilyMember::class);
    }

    public function lichHens()
    {
        return $this->hasMany(\App\Models\LichHen::class, 'user_id');
    }

    public function benhAns()
    {
        return $this->hasMany(\App\Models\BenhAn::class, 'user_id');
    }

    public function hoaDons()
    {
        return $this->hasMany(\App\Models\HoaDon::class, 'user_id');
    }

    // --- LOGIC KHÓA TÀI KHOẢN GIỮ NGUYÊN ---
    public function isLocked(): bool
    {
        if (!$this->locked_at) {
            return false;
        }
        if ($this->locked_until && now()->greaterThan($this->locked_until)) {
            $this->update([
                'locked_at' => null,
                'locked_until' => null,
                'login_attempts' => 0,
            ]);
            return false;
        }
        return true;
    }

    public function resetLoginAttempts(): void
    {
        $this->update([
            'login_attempts' => 0,
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
        ]);
    }

    public function incrementLoginAttempts(): void
    {
        $attempts = $this->login_attempts + 1;
        $data = ['login_attempts' => $attempts];
        if ($attempts >= 5) {
            $data['locked_at'] = now();
            $data['locked_until'] = now()->addMinutes(30);
        }
        $this->update($data);
    }
}

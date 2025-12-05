<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles; // ✅ Giữ dòng này

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles; // ✅ Giữ dòng này để nạp method assignRole, hasRole...

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Vẫn giữ cột role để lưu legacy nếu cần
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
    ];

    // --- ❌ XÓA HOẶC COMMENT ĐOẠN NÀY (Vì Spatie đã có sẵn) ---
    /*
    public function roleKey(): string { ... }

    // Hàm này đang GHI ĐÈ hàm của Spatie -> Gây lỗi logic
    public function hasRole(string $role): bool { ... }

    public function hasAnyRole(array $roles): bool { ... }
    */
    // -----------------------------------------------------------

    // --- ✅ CẬP NHẬT CÁC HÀM TIỆN ÍCH DÙNG LOGIC SPATIE ---
    // Thay vì check cột string, ta tận dụng hàm hasRole() chuẩn của thư viện

    public function isAdmin(): bool
    {
        // Spatie sẽ tự check trong bảng phân quyền
        return $this->hasRole('admin') || $this->role === 'admin';
        // (Thêm || check cột cũ để tương thích ngược dữ liệu cũ chưa migrate)
    }

    public function isDoctor(): bool
    {
        return $this->hasRole('doctor') || $this->role === 'doctor';
    }

    public function isStaff(): bool
    {
        return $this->hasRole('staff') || $this->role === 'staff';
    }

    public function isPatient(): bool
    {
        return $this->hasRole('patient') || $this->role === 'patient';
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

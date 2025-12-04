<?php

namespace App\Policies;

use App\Models\User;
use App\Models\NhanVien;

class NhanVienPolicy
{
    public function viewAny(User $user): bool 
    { 
        return in_array($user->role, ['admin', 'staff']); 
    }

    public function view(User $user, NhanVien $nv): bool 
    { 
        // Admin toàn quyền, staff chỉ xem hồ sơ của mình
        if ($user->role === 'admin') return true;
        if ($user->role === 'staff' && $nv->user_id === $user->id) return true;
        return false;
    }

    public function create(User $user): bool 
    { 
        return $user->role === 'admin'; 
    }

    public function update(User $user, NhanVien $nv): bool 
    { 
        // Admin toàn quyền, staff chỉ sửa hồ sơ của mình
        if ($user->role === 'admin') return true;
        if ($user->role === 'staff' && $nv->user_id === $user->id) return true;
        return false;
    }

    public function delete(User $user, NhanVien $nv): bool 
    { 
        return $user->role === 'admin'; 
    }

    public function addShift(User $user, NhanVien $nv): bool
    {
        // Chỉ admin mới được phân ca
        return $user->role === 'admin';
    }

    public function viewHistory(User $user, NhanVien $nv): bool
    {
        // Admin toàn quyền, staff chỉ xem lịch sử của mình
        if ($user->role === 'admin') return true;
        if ($user->role === 'staff' && $nv->user_id === $user->id) return true;
        return false;
    }

    public function exportShifts(User $user): bool
    {
        // Chỉ admin mới được xuất báo cáo ca
        return $user->role === 'admin';
    }
}
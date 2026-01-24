<?php

namespace App\Policies;

use App\Models\LichHen;
use App\Models\User;

class LichHenPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'doctor', 'staff']);
    }

    public function view(User $user, LichHen $lichHen): bool
    {
        if ($user->role === 'admin') return true;
        if ($user->role === 'doctor' && optional($user->bacSi)->id === $lichHen->bac_si_id) return true;
        if ($user->role === 'patient' && $lichHen->user_id === $user->id) return true;
        if ($user->role === 'staff') return true; // staff can view all
        return false;
    }

    public function checkin(User $user, LichHen $lichHen): bool
    {
        if ($user->role === 'admin') return true;
        if ($user->role === 'staff') {
            return $lichHen->isCheckinAllowed();
        }
        return false;
    }

    public function callNext(User $user, LichHen $lichHen): bool
    {
        if ($user->role === 'admin') return true;
        if ($user->role === 'staff') return $lichHen->trang_thai === LichHen::STATUS_CHECKED_IN_VN;
        return false;
    }

    public function startExam(User $user, LichHen $lichHen): bool
    {
        if ($user->role === 'admin') return true;
        if ($user->role === 'doctor' && optional($user->bacSi)->id === $lichHen->bac_si_id) {
            return $lichHen->canStartExam();
        }
        return false;
    }

    public function completeExam(User $user, LichHen $lichHen): bool
    {
        if ($user->role === 'admin') return true;
        if ($user->role === 'doctor' && optional($user->bacSi)->id === $lichHen->bac_si_id) {
            return $lichHen->canCompleteExam();
        }
        return false;
    }
}

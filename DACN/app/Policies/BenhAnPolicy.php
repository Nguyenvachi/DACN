<?php

namespace App\Policies;

use App\Models\BenhAn;
use App\Models\User;

class BenhAnPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff', 'doctor', 'patient'], true);
    }

    public function view(User $user, BenhAn $record): bool
    {
        if (in_array($user->role, ['admin', 'staff'], true)) return true;
        if ($user->role === 'doctor' && $user->bacSi && $user->bacSi->id === $record->bac_si_id) return true;
        if ($user->role === 'patient' && $user->id === $record->user_id) return true;
        return false;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'doctor'], true);
    }

    public function update(User $user, BenhAn $record): bool
    {
        if ($user->role === 'admin') return true;
        return $user->role === 'doctor' && $user->bacSi && $user->bacSi->id === $record->bac_si_id;
    }

    public function delete(User $user, BenhAn $record): bool
    {
        return $this->update($user, $record);
    }
}

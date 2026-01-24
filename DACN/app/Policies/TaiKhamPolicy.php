<?php

namespace App\Policies;

use App\Models\TaiKham;
use App\Models\User;

class TaiKhamPolicy
{
    public function view(User $user, TaiKham $record): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        $benhAn = $record->benhAn;
        if (!$benhAn) {
            return false;
        }

        if ($user->isPatient()) {
            return (int) $benhAn->user_id === (int) $user->id;
        }

        if ($user->isDoctor() && $user->bacSi) {
            return (int) $benhAn->bac_si_id === (int) $user->bacSi->id;
        }

        if ($user->isStaff()) {
            return true;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isDoctor() || $user->isStaff() || $user->isPatient();
    }

    public function update(User $user, TaiKham $record): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($record->is_locked) {
            return false;
        }

        $benhAn = $record->benhAn;
        if (!$benhAn) {
            return false;
        }

        if ($user->isDoctor() && $user->bacSi) {
            return (int) $benhAn->bac_si_id === (int) $user->bacSi->id;
        }

        if ($user->isStaff()) {
            return true;
        }

        if ($user->isPatient()) {
            return (int) $benhAn->user_id === (int) $user->id
                && in_array($record->trang_thai, [TaiKham::STATUS_PENDING_VN, TaiKham::STATUS_CONFIRMED_VN], true);
        }

        return false;
    }
}

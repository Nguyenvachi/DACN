<?php

namespace App\Policies;

use App\Models\BacSi;
use App\Models\NoiSoi;
use App\Models\User;

class NoiSoiPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isStaff() || $user->isDoctor() || $user->isPatient();
    }

    public function view(User $user, NoiSoi $record): bool
    {
        if ($user->isAdmin() || $user->isStaff()) {
            return true;
        }

        if ($user->isPatient()) {
            return (int) $record->user_id === (int) $user->id;
        }

        if ($user->isDoctor()) {
            $bacSi = $user->bacSi;
            if (! $bacSi) {
                return false;
            }

            if (!empty($record->bac_si_chi_dinh_id) && (int) $record->bac_si_chi_dinh_id === (int) $bacSi->id) {
                return true;
            }

            if (!empty($record->bac_si_noi_soi_id) && (int) $record->bac_si_noi_soi_id === (int) $bacSi->id) {
                return true;
            }

            return false;
        }

        return false;
    }

    public function download(User $user, NoiSoi $record): bool
    {
        return $this->view($user, $record) && $record->hasResult();
    }

    public function review(User $user, NoiSoi $record): bool
    {
        if (! $user->isDoctor()) {
            return false;
        }

        $bacSi = $user->bacSi;
        if (! $bacSi) {
            return false;
        }

        return !empty($record->bac_si_chi_dinh_id) && (int) $record->bac_si_chi_dinh_id === (int) $bacSi->id;
    }
}

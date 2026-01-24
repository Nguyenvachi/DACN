<?php

namespace App\Policies;

use App\Models\BacSi;
use App\Models\SieuAm;
use App\Models\User;

class SieuAmPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isStaff() || $user->isDoctor() || $user->isPatient();
    }

    public function view(User $user, SieuAm $record): bool
    {
        if ($user->isAdmin() || $user->isStaff()) {
            return true;
        }

        if ($user->isPatient()) {
            return (int) $record->user_id === (int) $user->id;
        }

        if ($user->isDoctor()) {
            $bacSi = $user->bacSi;
            if (!$bacSi) {
                return false;
            }

            $chiDinhId = $record->bac_si_chi_dinh_id ?? $record->bac_si_id;
            if (!empty($chiDinhId) && (int) $chiDinhId === (int) $bacSi->id) {
                return true;
            }

            if (!empty($record->bac_si_sieu_am_id) && (int) $record->bac_si_sieu_am_id === (int) $bacSi->id) {
                return true;
            }

            return false;
        }

        return false;
    }

    public function download(User $user, SieuAm $record): bool
    {
        return $this->view($user, $record) && $record->hasFile();
    }

    public function review(User $user, SieuAm $record): bool
    {
        if (!$user->isDoctor()) {
            return false;
        }

        $bacSi = $user->bacSi;
        if (!$bacSi) {
            return false;
        }

        $chiDinhId = $record->bac_si_chi_dinh_id ?? $record->bac_si_id;
        return !empty($chiDinhId) && (int) $chiDinhId === (int) $bacSi->id;
    }
}

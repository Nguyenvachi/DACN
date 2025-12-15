<?php

namespace App\Policies;

use App\Models\BenhAn;
use App\Models\User;

class BenhAnPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isStaff() || $user->isDoctor() || $user->isPatient();
    }

    public function view(User $user, BenhAn $record): bool
    {
        if ($user->isAdmin() || $user->isStaff()) return true;
        if ($user->isDoctor() && $user->bacSi && $user->bacSi->id === $record->bac_si_id) return true;
        if ($user->isPatient() && $user->id === $record->user_id) return true;
        return false;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isDoctor();
    }

    public function update(User $user, BenhAn $record): bool
    {
        if ($user->isAdmin()) return true;
        return $user->isDoctor() && $user->bacSi && $user->bacSi->id === $record->bac_si_id;
    }

    public function delete(User $user, BenhAn $record): bool
    {
        return $this->update($user, $record);
    }

    // THÊM: Method cho export PDF (nếu chưa có)
    public function exportPdf(User $user, BenhAn $record): bool
    {
        return $this->view($user, $record) && $user->hasPermissionTo('export-data');
    }

    // THÊM: Method cho audit log (nếu chưa có)
    public function audit(User $user, BenhAn $record): bool
    {
        return $user->isAdmin() || ($user->isDoctor() && $user->bacSi && $user->bacSi->id === $record->bac_si_id);
    }

    // THÊM: Method tổng hợp cho admin actions
    public function manage(User $user): bool
    {
        return $user->hasAnyPermission(['create-medical-records', 'edit-medical-records', 'delete-medical-records']);
    }
}

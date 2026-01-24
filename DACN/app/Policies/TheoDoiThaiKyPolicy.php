<?php

namespace App\Policies;

use App\Models\TheoDoiThaiKy;
use App\Models\User;

class TheoDoiThaiKyPolicy
{
    public function view(User $user, TheoDoiThaiKy $record): bool
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

    public function update(User $user, TheoDoiThaiKy $record): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        // Khóa chỉnh sửa khi đã ghi nhận / lưu trữ (chuẩn vận hành); chỉ Admin được sửa
        if (in_array($record->trang_thai, [TheoDoiThaiKy::STATUS_RECORDED, TheoDoiThaiKy::STATUS_ARCHIVED], true)) {
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
                && $record->trang_thai === TheoDoiThaiKy::STATUS_SUBMITTED;
        }

        return false;
    }

    public function delete(User $user, TheoDoiThaiKy $record): bool
    {
        return $user->isAdmin();
    }
}

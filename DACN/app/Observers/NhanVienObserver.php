<?php

namespace App\Observers;

use App\Models\NhanVien;
use App\Models\NhanVienAudit;
use Illuminate\Support\Facades\Auth;

class NhanVienObserver
{
    public function created(NhanVien $nhanVien): void
    {
        NhanVienAudit::create([
            'nhan_vien_id' => $nhanVien->id,
            'user_id' => Auth::id(),
            'action' => 'created',
            'old_data' => null,
            'new_data' => $nhanVien->getAttributes(),
        ]);
    }

    public function updated(NhanVien $nhanVien): void
    {
        $changes = $nhanVien->getDirty();
        
        if (empty($changes)) {
            return;
        }

        $oldData = [];
        foreach ($changes as $key => $value) {
            $oldData[$key] = $nhanVien->getOriginal($key);
        }

        NhanVienAudit::create([
            'nhan_vien_id' => $nhanVien->id,
            'user_id' => Auth::id(),
            'action' => 'updated',
            'old_data' => $oldData,
            'new_data' => $changes,
        ]);
    }

    public function deleted(NhanVien $nhanVien): void
    {
        NhanVienAudit::create([
            'nhan_vien_id' => $nhanVien->id,
            'user_id' => Auth::id(),
            'action' => 'deleted',
            'old_data' => $nhanVien->getOriginal(),
            'new_data' => null,
        ]);
    }
}

<?php

namespace App\Observers;

use App\Models\HoaDon;

class HoaDonObserver
{
    public function updated(HoaDon $hoaDon): void
    {
        if ($hoaDon->wasChanged('trang_thai') && $hoaDon->lichHen) {
            $lichHen = $hoaDon->lichHen;
            if ($hoaDon->trang_thai === \App\Models\HoaDon::STATUS_PAID_VN) {
                $lichHen->payment_status = \App\Models\HoaDon::STATUS_PAID_VN;
                $lichHen->payment_method = $hoaDon->phuong_thuc;
                $lichHen->paid_at = now();
            } else {
                $lichHen->payment_status = $hoaDon->trang_thai;
            }
            $lichHen->saveQuietly();
        }
    }
}

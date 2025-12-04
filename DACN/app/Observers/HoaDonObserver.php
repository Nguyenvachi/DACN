<?php

namespace App\Observers;

use App\Models\HoaDon;

class HoaDonObserver
{
    public function updated(HoaDon $hoaDon): void
    {
        if ($hoaDon->wasChanged('trang_thai') && $hoaDon->lichHen) {
            $lichHen = $hoaDon->lichHen;
            if ($hoaDon->trang_thai === 'Đã thanh toán') {
                $lichHen->payment_status = 'Đã thanh toán';
                $lichHen->payment_method = $hoaDon->phuong_thuc;
                $lichHen->paid_at = now();
            } else {
                $lichHen->payment_status = $hoaDon->trang_thai;
            }
            $lichHen->saveQuietly();
        }
    }
}

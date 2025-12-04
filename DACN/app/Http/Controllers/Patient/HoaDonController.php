<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\HoaDon;

class HoaDonController extends Controller
{
    public function show(HoaDon $hoaDon)
    {
        abort_if($hoaDon->user_id !== auth()->id(), 403);
        return view('admin.hoadon.show', compact('hoaDon'));
    }
}

<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\NhanVien;
use App\Models\CaLamViecNhanVien;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Lấy thông tin nhân viên từ user_id
        $nhanVien = NhanVien::where('user_id', $user->id)->first();
        
        if (!$nhanVien) {
            return view('staff.dashboard', [
                'nhanVien' => null,
                'caHomNay' => collect(),
                'caTuanNay' => collect(),
            ]);
        }

        // Ca làm việc hôm nay
        $today = Carbon::today()->format('Y-m-d');
        $caHomNay = CaLamViecNhanVien::where('nhan_vien_id', $nhanVien->id)
            ->where('ngay', $today)
            ->orderBy('bat_dau')
            ->get();

        // Ca làm việc tuần này (từ thứ 2 đến chủ nhật)
        $startOfWeek = Carbon::now()->startOfWeek()->format('Y-m-d');
        $endOfWeek = Carbon::now()->endOfWeek()->format('Y-m-d');
        $caTuanNay = CaLamViecNhanVien::where('nhan_vien_id', $nhanVien->id)
            ->whereBetween('ngay', [$startOfWeek, $endOfWeek])
            ->orderBy('ngay')
            ->orderBy('bat_dau')
            ->get();

        return view('staff.dashboard', compact('nhanVien', 'caHomNay', 'caTuanNay'));
    }
}

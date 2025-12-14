<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\NhanVien;
use App\Models\CaLamViecNhanVien;
use App\Models\BenhAn;
use App\Models\LichHen;
use App\Models\HoaDon;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Lấy thông tin nhân viên từ user_id
        $nhanVien = NhanVien::with('user')->where('user_id', $user->id)->first();

        if (!$nhanVien) {
            return view('staff.dashboard', [
                'nhanVien' => null,
                'caHomNay' => collect(),
                'caTuanNay' => collect(),
                'statistics' => [],
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

        // Thống kê công việc hôm nay
        $statistics = [
            // Lịch hẹn hôm nay
            'lich_hen_hom_nay' => LichHen::whereDate('ngay_hen', $today)->count(),
            'lich_hen_cho_xac_nhan' => LichHen::whereDate('ngay_hen', $today)
                ->where('trang_thai', 'Chờ xác nhận')
                ->count(),

            // Bệnh án hoàn thành cần tạo hóa đơn (chưa có hóa đơn)
            'benh_an_can_tao_hoa_don' => BenhAn::where('trang_thai', 'Hoàn thành')
                ->whereDoesntHave('lichHen.hoaDon')
                ->whereNull('lich_hen_id')
                ->orWhereHas('lichHen', function ($q) {
                    $q->whereDoesntHave('hoaDon');
                })
                ->count(),

            // Hóa đơn chưa thanh toán
            'hoa_don_chua_thanh_toan' => HoaDon::where('trang_thai', 'Chưa thanh toán')
                ->whereDate('created_at', '>=', Carbon::today()->subDays(7))
                ->count(),

            // Tổng hóa đơn hôm nay
            'hoa_don_hom_nay' => HoaDon::whereDate('created_at', $today)->count(),
        ];

        // Danh sách bệnh án cần xử lý (5 bệnh án gần nhất)
        $benhAnCanXuLy = BenhAn::with(['benhNhan', 'bacSi'])
            ->where('trang_thai', 'Hoàn thành')
            ->where(function ($query) {
                $query->whereDoesntHave('lichHen.hoaDon')
                    ->orWhereHas('lichHen', function ($q) {
                        $q->whereDoesntHave('hoaDon');
                    });
            })
            ->orderByDesc('updated_at')
            ->limit(5)
            ->get();

        return view('staff.dashboard', compact('nhanVien', 'caHomNay', 'caTuanNay', 'statistics', 'benhAnCanXuLy'));
    }
}

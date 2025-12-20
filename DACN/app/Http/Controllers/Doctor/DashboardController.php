<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\BacSi;
use App\Models\LichHen;
use App\Models\BenhAn;
use App\Models\DanhGia;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Controller Dashboard cho Bác sĩ
 * File mẹ: routes/web.php - Route: doctor.dashboard
 * Xử lý thống kê và hiển thị dữ liệu tổng quan cho bác sĩ
 */
class DashboardController extends Controller
{
    /**
     * Hiển thị dashboard tổng quan cho bác sĩ đang đăng nhập
     */
    public function index()
    {
        // Lấy thông tin bác sĩ từ user_id
        $bacSi = BacSi::where('user_id', auth()->id())->first();

        if (!$bacSi) {
            return redirect()->route('dashboard')
                ->with('error', 'Tài khoản bác sĩ chưa được gắn với hồ sơ BacSi.');
        }

        // === THỐNG KÊ TỔNG QUAN ===
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // Lịch hẹn hôm nay
        $appointmentsToday = LichHen::where('bac_si_id', $bacSi->id)
            ->whereDate('ngay_hen', $today)
            ->count();

        // Lịch hẹn tháng này
        $appointmentsThisMonth = LichHen::where('bac_si_id', $bacSi->id)
            ->where('ngay_hen', '>=', $thisMonth)
            ->count();

        // Tổng số bệnh nhân đã khám (unique users)
        $totalPatients = LichHen::where('bac_si_id', $bacSi->id)
            ->distinct('user_id')
            ->count('user_id');

        // Rating trung bình
        $avgRating = DanhGia::where('bac_si_id', $bacSi->id)
            ->where('trang_thai', 'approved')
            ->avg('rating');
        $avgRating = $avgRating ? round($avgRating, 1) : 0;

        // Tổng đánh giá
        $totalReviews = DanhGia::where('bac_si_id', $bacSi->id)
            ->where('trang_thai', 'approved')
            ->count();

        // === LỊCH HẸN SẮP TỚI (7 ngày tới) ===
        $upcomingAppointments = LichHen::where('bac_si_id', $bacSi->id)
            ->whereDate('ngay_hen', '>=', $today)
            ->whereDate('ngay_hen', '<=', $today->copy()->addDays(7))
            ->whereIn('trang_thai', ['Chờ xác nhận', 'Đã xác nhận'])
            ->with(['user', 'dichVu', 'hoaDon'])
            ->orderBy('ngay_hen')
            ->orderBy('thoi_gian_hen')
            ->limit(10)
            ->get();

        // === LỊCH HẸN HÔM NAY CHI TIẾT ===
        $todayAppointments = LichHen::where('bac_si_id', $bacSi->id)
            ->whereDate('ngay_hen', $today)
            ->with(['user', 'dichVu', 'hoaDon'])
            ->orderBy('thoi_gian_hen')
            ->get();

        // === LỊCH HẸN CHỜ XÁC NHẬN ===
        $pendingAppointments = LichHen::where('bac_si_id', $bacSi->id)
            ->where('trang_thai', 'Chờ xác nhận')
            ->count();

        // === BIỂU ĐỒ LỊCH HẸN 7 NGÀY QUA ===
        $appointmentsChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = LichHen::where('bac_si_id', $bacSi->id)
                ->whereDate('ngay_hen', $date)
                ->count();

            $appointmentsChart[] = [
                'date' => $date->format('d/m'),
                'count' => $count
            ];
        }

        // === BIỂU ĐỒ TRẠNG THÁI LỊCH HẸN THÁNG NÀY ===
        $statusStats = LichHen::where('bac_si_id', $bacSi->id)
            ->where('ngay_hen', '>=', $thisMonth)
            ->select('trang_thai', DB::raw('count(*) as total'))
            ->groupBy('trang_thai')
            ->pluck('total', 'trang_thai')
            ->toArray();

        // === BỆNH ÁN GẦN ĐÂY ===
        $recentMedicalRecords = BenhAn::where('bac_si_id', $bacSi->id)
            ->with(['benhNhan', 'lichHen'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // === ĐÁNH GIÁ MỚI NHẤT ===
        $recentReviews = DanhGia::where('bac_si_id', $bacSi->id)
            ->where('trang_thai', 'approved')
            ->with('user')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // === THỐNG KÊ BỆNH NHÂN MỚI THEO THÁNG (6 tháng gần nhất) ===
        $newPatientsChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthStart = Carbon::now()->subMonths($i)->startOfMonth();
            $monthEnd = Carbon::now()->subMonths($i)->endOfMonth();

            // Đếm số bệnh nhân mới (lịch hẹn đầu tiên của họ với bác sĩ này)
            $newPatients = LichHen::where('bac_si_id', $bacSi->id)
                ->whereBetween('ngay_hen', [$monthStart, $monthEnd])
                ->select('user_id')
                ->distinct()
                ->get()
                ->filter(function ($lich) use ($bacSi, $monthStart) {
                    // Kiểm tra xem đây có phải lịch hẹn đầu tiên không
                    $firstAppointment = LichHen::where('bac_si_id', $bacSi->id)
                        ->where('user_id', $lich->user_id)
                        ->orderBy('ngay_hen')
                        ->first();

                    return $firstAppointment &&
                           Carbon::parse($firstAppointment->ngay_hen)->gte($monthStart);
                })
                ->count();

            $newPatientsChart[] = [
                'month' => $monthStart->format('m/Y'),
                'count' => $newPatients
            ];
        }

        // === PHÂN PHỐI RATING ===
        $ratingDistribution = DanhGia::where('bac_si_id', $bacSi->id)
            ->where('trang_thai', 'approved')
            ->select('rating', DB::raw('count(*) as count'))
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->pluck('count', 'rating')
            ->toArray();

        // Đảm bảo có đủ 5 sao (1-5)
        for ($i = 1; $i <= 5; $i++) {
            if (!isset($ratingDistribution[$i])) {
                $ratingDistribution[$i] = 0;
            }
        }
        krsort($ratingDistribution);

        return view('doctor.dashboard', compact(
            'bacSi',
            'appointmentsToday',
            'appointmentsThisMonth',
            'totalPatients',
            'avgRating',
            'totalReviews',
            'pendingAppointments',
            'upcomingAppointments',
            'todayAppointments',
            'appointmentsChart',
            'statusStats',
            'recentMedicalRecords',
            'recentReviews',
            'newPatientsChart',
            'ratingDistribution'
        ));
    }
}

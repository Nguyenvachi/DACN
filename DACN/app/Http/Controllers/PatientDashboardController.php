<?php

namespace App\Http\Controllers;

use App\Models\LichHen;
use App\Models\BenhAn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PatientDashboardController extends Controller
{
    /**
     * Dashboard sức khỏe cho bệnh nhân
     */
    public function index()
    {
        $user = Auth::user();

        // Lấy lịch hẹn sắp tới (5 appointment gần nhất)
        $upcomingAppointments = LichHen::where('user_id', $user->id)
            ->where('ngay_hen', '>=', Carbon::today())
            ->whereIn('trang_thai', ['pending', 'confirmed'])
            ->orderBy('ngay_hen')
            ->orderBy('thoi_gian_hen')
            ->take(5)
            ->with(['bacSi.user', 'dichVu'])
            ->get();

        // Lấy bệnh án gần đây (3 records)
        $recentMedicalRecords = BenhAn::where('user_id', $user->id)
            ->orderBy('ngay_kham', 'desc')
            ->take(3)
            ->with(['bacSi.user', 'dichVu'])
            ->get();

        // Lấy thông tin y tế
        $profile = $user->patientProfile;

        // Health stats
        $healthStats = [
            'bmi' => $profile ? $profile->bmi : null,
            'bmi_category' => $profile ? $profile->bmi_category : null,
            'blood_type' => $profile ? $profile->nhom_mau : null,
            'height' => $profile ? $profile->chieu_cao : null,
            'weight' => $profile ? $profile->can_nang : null,
            'allergies_count' => $profile && $profile->allergies ? count($profile->allergies) : 0,
        ];

        // Thống kê tổng quan
        $statistics = [
            'total_appointments' => LichHen::where('user_id', $user->id)->count(),
            'completed_appointments' => LichHen::where('user_id', $user->id)
                ->where('trang_thai', 'completed')
                ->count(),
            'total_medical_records' => BenhAn::where('user_id', $user->id)->count(),
            'pending_appointments' => LichHen::where('user_id', $user->id)
                ->where('trang_thai', 'pending')
                ->count(),
        ];

        // Lấy dữ liệu BMI history (6 tháng gần nhất) nếu có
        // (giả sử trong tương lai có table patient_health_logs để track BMI theo thời gian)
        $bmiHistory = [
            'labels' => ['Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
            'data' => [22.5, 22.8, 23.0, 22.9, 22.7, $profile ? $profile->bmi : 0],
        ];

        return view('patient.dashboard', compact(
            'upcomingAppointments',
            'recentMedicalRecords',
            'healthStats',
            'statistics',
            'bmiHistory',
            'profile'
        ));
    }
}

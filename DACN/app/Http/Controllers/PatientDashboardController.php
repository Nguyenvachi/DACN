<?php

namespace App\Http\Controllers;

use App\Models\LichHen;
use App\Models\BenhAn;
use App\Models\HoaDon;
use App\Models\DonThuoc;
use App\Models\XetNghiem;
use App\Models\DanhGia;
use App\Models\SieuAm; // THÊM
use App\Models\XQuang; // THÊM
use App\Models\NoiSoi; // THÊM
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PatientDashboardController extends Controller
{
    /**
     * Dashboard sức khỏe cho bệnh nhân với đầy đủ thống kê
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->patientProfile;

        // === THỐNG KÊ TỔNG QUAN ===
        $statistics = [
            'total_appointments' => LichHen::where('user_id', $user->id)->count(),
            'upcoming_appointments' => LichHen::where('user_id', $user->id)
                ->where('ngay_hen', '>=', Carbon::today())
                ->whereIn('trang_thai', [
                    \App\Models\LichHen::STATUS_PENDING_VN,
                    \App\Models\LichHen::STATUS_CONFIRMED_VN,
                    \App\Models\LichHen::STATUS_CHECKED_IN_VN,
                    \App\Models\LichHen::STATUS_IN_PROGRESS_VN
                ])
                ->count(),
            'completed_appointments' => LichHen::where('user_id', $user->id)
                ->where('trang_thai', \App\Models\LichHen::STATUS_COMPLETED_VN)
                ->count(),
            'pending_appointments' => LichHen::where('user_id', $user->id)
                ->where('trang_thai', \App\Models\LichHen::STATUS_PENDING_VN)
                ->count(),
            'total_medical_records' => BenhAn::where('user_id', $user->id)->count(),
            'unpaid_invoices' => HoaDon::where('user_id', $user->id)
                ->where('trang_thai', '!=', \App\Models\HoaDon::STATUS_PAID_VN)
                ->count(),
            'total_prescriptions' => DonThuoc::whereHas('benhAn', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),
            'total_tests' => XetNghiem::whereHas('benhAn', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),
            // THÊM: thống kê siêu âm (độc lập với xét nghiệm)
            // Gợi ý bám theo pattern Xét nghiệm: whereHas('benhAn'...) để đảm bảo đúng patient sở hữu hồ sơ
            'total_ultrasounds' => SieuAm::whereHas('benhAn', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),

            // THÊM: thống kê X-Quang (độc lập với xét nghiệm/siêu âm)
            'total_xquangs' => XQuang::whereHas('benhAn', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),

            // THÊM: thống kê Nội soi
            'total_noisois' => NoiSoi::whereHas('benhAn', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),
        ];

        // === LỊCH HẸN SẮP TỚI (5 gần nhất) ===
        $upcomingAppointments = LichHen::where('user_id', $user->id)
            ->where('ngay_hen', '>=', Carbon::today())
            ->whereIn('trang_thai', [
                \App\Models\LichHen::STATUS_PENDING_VN,
                \App\Models\LichHen::STATUS_CONFIRMED_VN,
                \App\Models\LichHen::STATUS_CHECKED_IN_VN,
                \App\Models\LichHen::STATUS_IN_PROGRESS_VN
            ])
            ->orderBy('ngay_hen')
            ->orderBy('thoi_gian_hen')
            ->take(5)
            ->with(['bacSi.user', 'dichVu'])
            ->get();

        // === BỆNH ÁN GẦN ĐÂY (3 records) ===
        $recentMedicalRecords = BenhAn::where('user_id', $user->id)
            ->orderBy('ngay_kham', 'desc')
            ->take(3)
            ->with(['bacSi.user', 'dichVu'])
            ->get();

        // === HÓA ĐƠN CHƯA THANH TOÁN ===
        $unpaidInvoices = HoaDon::where('user_id', $user->id)
            ->where('trang_thai', '!=', 'Đã thanh toán')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->with(['lichHen.bacSi.user', 'lichHen.dichVu'])
            ->get();

        // === CHỈ SỐ SỨC KHỎE ===
        $healthStats = [
            'bmi' => $profile ? $profile->bmi : null,
            'bmi_category' => $profile ? $profile->bmi_category : null,
            'blood_type' => $profile ? $profile->nhom_mau : null,
            'height' => $profile ? $profile->chieu_cao : null,
            'weight' => $profile ? $profile->can_nang : null,
            'allergies_count' => $profile && $profile->allergies ? count($profile->allergies) : 0,
            'last_checkup' => BenhAn::where('user_id', $user->id)
                ->latest('ngay_kham')
                ->value('ngay_kham'),
        ];

        // === BIỂU ĐỒ LỊCH HẸN 6 THÁNG GẦN NHẤT ===
        $appointmentChartData = $this->getAppointmentChartData($user->id);

        // === XÉT NGHIỆM GẦN ĐÂY ===
        $recentTests = XetNghiem::whereHas('benhAn', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->with(['bacSi', 'benhAn'])
            ->get();

        // === ĐÁNH GIÁ CỦA TÔI ===
        $myReviews = DanhGia::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->with(['bacSi.user'])
            ->get();

        // === THÔNG BÁO CHƯA ĐỌC ===
        $unreadNotifications = $user->unreadNotifications()->take(5)->get();

        return view('patient.dashboard-modern', compact(
            'statistics',
            'upcomingAppointments',
            'recentMedicalRecords',
            'unpaidInvoices',
            'healthStats',
            'appointmentChartData',
            'recentTests',
            'myReviews',
            'unreadNotifications',
            'profile'
        ));
    }

    /**
     * Lấy dữ liệu biểu đồ lịch hẹn 6 tháng
     */
    private function getAppointmentChartData($userId)
    {
        $months = [];
        $completed = [];
        $cancelled = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = $month->format('m/Y');

            $completedCount = LichHen::where('user_id', $userId)
                ->whereYear('ngay_hen', $month->year)
                ->whereMonth('ngay_hen', $month->month)
                ->where('trang_thai', LichHen::STATUS_COMPLETED)
                ->count();

            $cancelledCount = LichHen::where('user_id', $userId)
                ->whereYear('ngay_hen', $month->year)
                ->whereMonth('ngay_hen', $month->month)
                ->where('trang_thai', LichHen::STATUS_CANCELLED)
                ->count();

            $completed[] = $completedCount;
            $cancelled[] = $cancelledCount;
        }

        return [
            'labels' => $months,
            'completed' => $completed,
            'cancelled' => $cancelled,
        ];
    }
}

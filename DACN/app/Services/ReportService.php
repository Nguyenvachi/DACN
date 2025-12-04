<?php

namespace App\Services;

use App\Models\LichHen;
use App\Models\HoaDon;
use App\Models\User;
use App\Models\DonHang;
use App\Models\DonHangItem;
use App\Models\HoanTien;
use App\Models\Thuoc;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function summary($from = null, $to = null)
    {
        $from = $from ?: now()->subDays(30)->toDateString();
        $to = $to ?: now()->toDateString();

        return [
            'from' => $from,
            'to' => $to,
            'appointments' => LichHen::whereBetween('ngay_hen', [$from, $to])->count(),
            // SỮA: Dùng updated_at thay vì created_at để bắt được thanh toán
            'revenue' => HoaDon::whereBetween('updated_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->where('trang_thai', 'Đã thanh toán')
                ->sum('tong_tien'),
            'paid_invoices' => HoaDon::whereBetween('updated_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->where('trang_thai', 'Đã thanh toán')
                ->count(),
        ];
    }

    public function appointmentStatusCounts($from = null, $to = null)
    {
        $from = $from ?: now()->subDays(30)->toDateString();
        $to = $to ?: now()->toDateString();

        return LichHen::whereBetween('ngay_hen', [$from, $to])
            ->selectRaw('trang_thai, COUNT(*) as count')
            ->groupBy('trang_thai')
            ->pluck('count', 'trang_thai')
            ->toArray();
    }

    public function appointmentDaily($from = null, $to = null)
    {
        $from = $from ?: now()->subDays(30)->toDateString();
        $to = $to ?: now()->toDateString();

        return LichHen::whereBetween('ngay_hen', [$from, $to])
            ->selectRaw('DATE(ngay_hen) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();
    }

    public function revenueByService($from = null, $to = null)
    {
        $from = $from ?: now()->subDays(30)->toDateString();
        $to = $to ?: now()->toDateString();

        return HoaDon::join('lich_hens', 'hoa_dons.lich_hen_id', '=', 'lich_hens.id')
            ->join('dich_vus', 'lich_hens.dich_vu_id', '=', 'dich_vus.id')
            // SỮA: Dùng updated_at thay vì created_at
            ->whereBetween('hoa_dons.updated_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->where('hoa_dons.trang_thai', 'Đã thanh toán')
            ->selectRaw('dich_vus.ten_dich_vu as label, SUM(hoa_dons.tong_tien) as total')
            ->groupBy('dich_vus.id', 'dich_vus.ten_dich_vu')
            ->get()
            ->toArray();
    }

    public function revenueByDoctor($from = null, $to = null)
    {
        $from = $from ?: now()->subDays(30)->toDateString();
        $to = $to ?: now()->toDateString();

        return HoaDon::join('lich_hens', 'hoa_dons.lich_hen_id', '=', 'lich_hens.id')
            ->join('bac_sis', 'lich_hens.bac_si_id', '=', 'bac_sis.id')
            // SỮA: Dùng updated_at thay vì created_at
            ->whereBetween('hoa_dons.updated_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->where('hoa_dons.trang_thai', 'Đã thanh toán')
            ->selectRaw('bac_sis.ho_ten as label, SUM(hoa_dons.tong_tien) as total')
            ->groupBy('bac_sis.id', 'bac_sis.ho_ten')
            ->get()
            ->toArray();
    }

    public function revenueByGateway($from = null, $to = null)
    {
        $from = $from ?: now()->subDays(30)->toDateString();
        $to = $to ?: now()->toDateString();

        // SỬA: Dùng updated_at thay vì created_at
        return HoaDon::whereBetween('updated_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->where('trang_thai', 'Đã thanh toán')
            ->selectRaw('phuong_thuc as label, SUM(tong_tien) as total')
            ->groupBy('phuong_thuc')
            ->get()
            ->toArray();
    }

    // ==================== MỞ RỘNG: CÁC ENDPOINTS MỚI ====================

    /**
     * Thống kê bệnh nhân mới theo tháng (user role patient, created trong khoảng from-to)
     * Caching 5 phút
     */
    public function newPatientsMonthly($from = null, $to = null)
    {
        $from = $from ?: now()->subDays(30)->toDateString();
        $to = $to ?: now()->toDateString();

        $cacheKey = "report_new_patients_{$from}_{$to}";

        return Cache::remember($cacheKey, 300, function () use ($from, $to) {
            return User::whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->where('role', 'patient')
                ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('count', 'month')
                ->toArray();
        });
    }

    /**
     * Top 10 dịch vụ được sử dụng nhiều nhất (theo số lượt đặt lịch)
     * Caching 5 phút
     */
    public function topServices($from = null, $to = null, $limit = 10)
    {
        $from = $from ?: now()->subDays(30)->toDateString();
        $to = $to ?: now()->toDateString();

        $cacheKey = "report_top_services_{$from}_{$to}_{$limit}";

        return Cache::remember($cacheKey, 300, function () use ($from, $to, $limit) {
            return LichHen::join('dich_vus', 'lich_hens.dich_vu_id', '=', 'dich_vus.id')
                ->whereBetween('lich_hens.ngay_hen', [$from, $to])
                ->selectRaw('dich_vus.ten_dich_vu as label, COUNT(*) as count')
                ->groupBy('dich_vus.id', 'dich_vus.ten_dich_vu')
                ->orderByDesc('count')
                ->limit($limit)
                ->get()
                ->toArray();
        });
    }

    /**
     * Thống kê hoàn tiền (số lượng + tổng tiền)
     * Caching 5 phút
     */
    public function refundStatistics($from = null, $to = null)
    {
        $from = $from ?: now()->subDays(30)->toDateString();
        $to = $to ?: now()->toDateString();

        $cacheKey = "report_refunds_{$from}_{$to}";

        return Cache::remember($cacheKey, 300, function () use ($from, $to) {
            $stats = HoanTien::whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->selectRaw('
                    COUNT(*) as total_count,
                    SUM(so_tien) as total_amount,
                    AVG(so_tien) as avg_amount,
                    trang_thai,
                    COUNT(*) as count_by_status
                ')
                ->groupBy('trang_thai')
                ->get();

            $summary = [
                'total_count' => $stats->sum('total_count'),
                'total_amount' => $stats->sum('total_amount'),
                'avg_amount' => $stats->avg('avg_amount'),
                'by_status' => []
            ];

            foreach ($stats as $stat) {
                $summary['by_status'][$stat->trang_thai] = [
                    'count' => $stat->count_by_status,
                    'amount' => $stat->total_amount
                ];
            }

            return $summary;
        });
    }

    /**
     * Thống kê doanh số thuốc (từ bảng don_hangs + don_hang_items)
     * Trả về: Top thuốc bán chạy + tổng doanh thu thuốc
     * Caching 5 phút
     */
    public function medicineSalesStatistics($from = null, $to = null, $limit = 10)
    {
        $from = $from ?: now()->subDays(30)->toDateString();
        $to = $to ?: now()->toDateString();

        $cacheKey = "report_medicine_sales_{$from}_{$to}_{$limit}";

        return Cache::remember($cacheKey, 300, function () use ($from, $to, $limit) {
            // Tổng doanh thu thuốc
            $totalRevenue = DonHang::whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->where('trang_thai', 'Hoàn thành')
                ->sum('tong_tien');

            // Top thuốc bán chạy
            $topMedicines = DonHangItem::join('don_hangs', 'don_hang_items.don_hang_id', '=', 'don_hangs.id')
                ->join('thuocs', 'don_hang_items.thuoc_id', '=', 'thuocs.id')
                ->whereBetween('don_hangs.created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->where('don_hangs.trang_thai', 'Hoàn thành')
                ->selectRaw('
                    thuocs.ten as label,
                    SUM(don_hang_items.so_luong) as quantity,
                    SUM(don_hang_items.thanh_tien) as revenue
                ')
                ->groupBy('thuocs.id', 'thuocs.ten')
                ->orderByDesc('revenue')
                ->limit($limit)
                ->get()
                ->toArray();

            return [
                'total_revenue' => $totalRevenue,
                'top_medicines' => $topMedicines
            ];
        });
    }

    /**
     * So sánh với kỳ trước (để tính % tăng/giảm)
     * Tính toán metrics key cho kỳ hiện tại vs kỳ trước
     */
    public function compareWithPreviousPeriod($from = null, $to = null)
    {
        $from = $from ? Carbon::parse($from) : now()->subDays(30);
        $to = $to ? Carbon::parse($to) : now();

        $daysDiff = $from->diffInDays($to);
        $previousFrom = $from->copy()->subDays($daysDiff + 1);
        $previousTo = $from->copy()->subDay();

        $cacheKey = "report_compare_{$from->toDateString()}_{$to->toDateString()}";

        return Cache::remember($cacheKey, 300, function () use ($from, $to, $previousFrom, $previousTo) {
            // Kỳ hiện tại
            $current = $this->summary($from->toDateString(), $to->toDateString());

            // Kỳ trước
            $previous = $this->summary($previousFrom->toDateString(), $previousTo->toDateString());

            // Tính % thay đổi
            $calculateChange = function ($current, $previous) {
                if ($previous == 0) return $current > 0 ? 100 : 0;
                return round((($current - $previous) / $previous) * 100, 2);
            };

            return [
                'current' => $current,
                'previous' => $previous,
                'changes' => [
                    'appointments' => $calculateChange($current['appointments'], $previous['appointments']),
                    'revenue' => $calculateChange($current['revenue'], $previous['revenue']),
                    'paid_invoices' => $calculateChange($current['paid_invoices'], $previous['paid_invoices']),
                ]
            ];
        });
    }
}

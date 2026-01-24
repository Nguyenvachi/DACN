<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;
use App\Models\LichHen;

class ReportController extends Controller
{
    public function __construct(private ReportService $report) {}

    // Trang dashboard
    public function dashboard(Request $request)
    {
        $summary = $this->report->summary($request->from, $request->to);

        // MỞ RỘNG: Thêm so sánh với kỳ trước (Parent: app/Http/Controllers/Admin/ReportController.php)
        $comparison = $this->report->compareWithPreviousPeriod($request->from, $request->to);

        return view('admin.dashboard.index', compact('summary', 'comparison'));
    }

    // API JSON cho biểu đồ
    public function status(Request $request)
    {
        return response()->json($this->report->appointmentStatusCounts($request->from, $request->to));
    }

    public function daily(Request $request)
    {
        return response()->json($this->report->appointmentDaily($request->from, $request->to));
    }

    public function revenueByService(Request $request)
    {
        return response()->json($this->report->revenueByService($request->from, $request->to));
    }

    public function revenueByDoctor(Request $request)
    {
        return response()->json($this->report->revenueByDoctor($request->from, $request->to));
    }

    public function revenueByGateway(Request $request)
    {
        return response()->json($this->report->revenueByGateway($request->from, $request->to));
    }

    public function appointmentsStatus(Request $request)
    {
        $from = $request->get('from', now()->subDays(30)->format('Y-m-d'));
        $to = $request->get('to', now()->format('Y-m-d'));

        $data = LichHen::whereBetween('ngay_hen', [$from, $to])
            ->selectRaw('trang_thai, COUNT(*) as total')
            ->groupBy('trang_thai')
            ->pluck('total', 'trang_thai')
            ->toArray();

        return response()->json($data);
    }

    public function appointmentsDaily(Request $request)
    {
        $from = $request->get('from', now()->subDays(30)->format('Y-m-d'));
        $to = $request->get('to', now()->format('Y-m-d'));

        $data = LichHen::whereBetween('ngay_hen', [$from, $to])
            ->selectRaw('DATE(ngay_hen) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        return response()->json($data);
    }

    // ==================== MỞ RỘNG: CÁC ENDPOINTS MỚI ====================

    /**
     * API: Bệnh nhân mới theo tháng
     */
    public function newPatientsMonthly(Request $request)
    {
        return response()->json($this->report->newPatientsMonthly($request->from, $request->to));
    }

    /**
     * API: Top dịch vụ được sử dụng nhiều nhất
     */
    public function topServices(Request $request)
    {
        $limit = $request->get('limit', 10);
        return response()->json($this->report->topServices($request->from, $request->to, $limit));
    }

    /**
     * API: Thống kê hoàn tiền
     */
    public function refundStatistics(Request $request)
    {
        return response()->json($this->report->refundStatistics($request->from, $request->to));
    }

    /**
     * API: Thống kê doanh số thuốc
     */
    public function medicineSalesStatistics(Request $request)
    {
        $limit = $request->get('limit', 10);
        return response()->json($this->report->medicineSalesStatistics($request->from, $request->to, $limit));
    }

    /**
     * API: So sánh với kỳ trước
     */
    public function compareWithPrevious(Request $request)
    {
        return response()->json($this->report->compareWithPreviousPeriod($request->from, $request->to));
    }

    /**
     * Export CSV - Báo cáo tổng hợp
     * Type: appointments, revenue, services, doctors, medicines, refunds
     */
    public function exportCsv(Request $request)
    {
        $type = $request->get('type', 'summary');
        $from = $request->get('from', now()->subDays(30)->format('Y-m-d'));
        $to = $request->get('to', now()->format('Y-m-d'));

        $filename = "bao_cao_{$type}_{$from}_to_{$to}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($type, $from, $to) {
            $file = fopen('php://output', 'w');

            // Thêm BOM để Excel hiển thị đúng UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            switch ($type) {
                case 'summary':
                    $this->exportSummaryCsv($file, $from, $to);
                    break;
                case 'appointments':
                    $this->exportAppointmentsCsv($file, $from, $to);
                    break;
                case 'revenue_service':
                    $this->exportRevenueServiceCsv($file, $from, $to);
                    break;
                case 'revenue_doctor':
                    $this->exportRevenueDoctorCsv($file, $from, $to);
                    break;
                case 'medicines':
                    $this->exportMedicinesCsv($file, $from, $to);
                    break;
                case 'refunds':
                    $this->exportRefundsCsv($file, $from, $to);
                    break;
                case 'new_patients':
                    $this->exportNewPatientsCsv($file, $from, $to);
                    break;
                default:
                    fputcsv($file, ['Error', 'Invalid type']);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ==================== PRIVATE HELPERS CHO EXPORT CSV ====================

    private function exportSummaryCsv($file, $from, $to)
    {
        fputcsv($file, ['Báo cáo tổng hợp', "Từ {$from} đến {$to}"]);
        fputcsv($file, ['Chỉ số', 'Giá trị']);

        $summary = $this->report->summary($from, $to);
        fputcsv($file, ['Tổng lịch hẹn', $summary['appointments']]);
        fputcsv($file, ['Doanh thu (VNĐ)', number_format($summary['revenue'], 0, ',', '.')]);
        fputcsv($file, ['Hóa đơn đã thanh toán', $summary['paid_invoices']]);
    }

    private function exportAppointmentsCsv($file, $from, $to)
    {
        fputcsv($file, ['Ngày', 'Số lượng lịch hẹn']);

        $data = $this->report->appointmentDaily($from, $to);
        foreach ($data as $date => $count) {
            fputcsv($file, [$date, $count]);
        }
    }

    private function exportRevenueServiceCsv($file, $from, $to)
    {
        fputcsv($file, ['Dịch vụ', 'Doanh thu (VNĐ)']);

        $data = $this->report->revenueByService($from, $to);
        foreach ($data as $item) {
            fputcsv($file, [$item['label'], $item['total']]);
        }
    }

    private function exportRevenueDoctorCsv($file, $from, $to)
    {
        fputcsv($file, ['Bác sĩ', 'Doanh thu (VNĐ)']);

        $data = $this->report->revenueByDoctor($from, $to);
        foreach ($data as $item) {
            fputcsv($file, [$item['label'], $item['total']]);
        }
    }

    private function exportMedicinesCsv($file, $from, $to)
    {
        fputcsv($file, ['Thuốc', 'Số lượng bán', 'Doanh thu (VNĐ)']);

        $data = $this->report->medicineSalesStatistics($from, $to);
        foreach ($data['top_medicines'] as $item) {
            fputcsv($file, [$item['label'], $item['quantity'], $item['revenue']]);
        }

        fputcsv($file, []);
        fputcsv($file, ['Tổng doanh thu thuốc', '', number_format($data['total_revenue'], 0, ',', '.')]);
    }

    private function exportRefundsCsv($file, $from, $to)
    {
        fputcsv($file, ['Thống kê hoàn tiền', "Từ {$from} đến {$to}"]);
        fputcsv($file, []);

        $data = $this->report->refundStatistics($from, $to);
        fputcsv($file, ['Tổng số lượt hoàn tiền', $data['total_count']]);
        fputcsv($file, ['Tổng số tiền hoàn (VNĐ)', number_format($data['total_amount'], 0, ',', '.')]);
        fputcsv($file, ['Trung bình mỗi lượt (VNĐ)', number_format($data['avg_amount'], 0, ',', '.')]);

        fputcsv($file, []);
        fputcsv($file, ['Trạng thái', 'Số lượng', 'Số tiền (VNĐ)']);
        foreach ($data['by_status'] as $status => $info) {
            fputcsv($file, [$status, $info['count'], $info['amount']]);
        }
    }

    private function exportNewPatientsCsv($file, $from, $to)
    {
        fputcsv($file, ['Tháng', 'Số bệnh nhân mới']);

        $data = $this->report->newPatientsMonthly($from, $to);
        foreach ($data as $month => $count) {
            fputcsv($file, [$month, $count]);
        }
    }

}

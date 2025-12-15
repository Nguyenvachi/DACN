<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BacSi;
use App\Services\ShiftService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LichLamViecController extends Controller
{
    protected $shiftService;

    public function __construct(ShiftService $shiftService)
    {
        $this->shiftService = $shiftService;
    }

    public function index(BacSi $bacSi)
    {
        $phongs = \App\Models\Phong::orderBy('ten')->get();
        // Trả về view và truyền thông tin bác sĩ ra
        return view('admin.lichlamviec.index', compact('bacSi', 'phongs'));
    }

    public function store(Request $request, BacSi $bacSi)
    {
        // Validate dữ liệu
        $validated = $request->validate([
            'ngay_trong_tuan' => 'required|integer|between:0,6',
            'thangs' => 'nullable|array',
            'thangs.*' => 'integer|between:1,12',
            'thoi_gian_bat_dau' => 'required|date_format:H:i',
            'thoi_gian_ket_thuc' => 'required|date_format:H:i|after:thoi_gian_bat_dau',
            'phong_id' => 'nullable|integer|exists:phongs,id',
        ]);

        // Chuyển mảng tháng thành chuỗi comma-separated
        $thangsString = !empty($validated['thangs']) ? implode(',', $validated['thangs']) : null;

        // Kiểm tra xung đột
        $this->shiftService->checkConflictForRecurring(
            $bacSi->id,
            $validated['ngay_trong_tuan'],
            $validated['thoi_gian_bat_dau'],
            $validated['thoi_gian_ket_thuc'],
            $thangsString
        );

        // Tạo lịch làm việc mới
        $bacSi->lichLamViecs()->create([
            'ngay_trong_tuan' => $validated['ngay_trong_tuan'],
            'thangs' => $thangsString,
            'thoi_gian_bat_dau' => $validated['thoi_gian_bat_dau'],
            'thoi_gian_ket_thuc' => $validated['thoi_gian_ket_thuc'],
            'phong_id' => $validated['phong_id'] ?? null,
        ]);

        return back()->with('success', 'Đã thêm lịch làm việc thành công!');
    }

    public function destroy(BacSi $bacSi, $lichLamViecId)
    {
        $lichLamViec = $bacSi->lichLamViecs()->findOrFail($lichLamViecId);
        $lichLamViec->delete();

        return back()->with('success', 'Xóa lịch làm việc thành công!');
    }

    /**
     * Export báo cáo lịch làm việc của bác sĩ
     *
     * @param BacSi $bacSi
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportReport(BacSi $bacSi, Request $request)
    {
        $format = $request->get('format', 'pdf'); // pdf hoặc csv
        $startDate = $request->get('start_date')
            ? Carbon::parse($request->get('start_date'))
            : Carbon::now()->startOfMonth();
        $endDate = $request->get('end_date')
            ? Carbon::parse($request->get('end_date'))
            : Carbon::now()->endOfMonth();

        // Lấy lịch làm việc định kỳ
        $lichLamViecs = $bacSi->lichLamViecs()
            ->with('phong')
            ->orderBy('ngay_trong_tuan')
            ->orderBy('thoi_gian_bat_dau')
            ->get();

        // Lấy lịch nghỉ trong khoảng thời gian
        $lichNghis = $bacSi->lichNghis()
            ->whereBetween('ngay', [$startDate, $endDate])
            ->orderBy('ngay')
            ->get();

        // Lấy ca điều chỉnh trong khoảng thời gian
        $caDieuChinhs = $bacSi->caDieuChinhs()
            ->whereBetween('ngay', [$startDate, $endDate])
            ->orderBy('ngay')
            ->get();

        $data = [
            'bacSi' => $bacSi,
            'lichLamViecs' => $lichLamViecs,
            'lichNghis' => $lichNghis,
            'caDieuChinhs' => $caDieuChinhs,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];

        if ($format === 'csv') {
            return $this->exportCsv($data);
        }

        return $this->exportPdf($data);
    }

    /**
     * Export PDF
     */
    private function exportPdf(array $data): \Illuminate\Http\Response
    {
        $pdf = Pdf::loadView('admin.lichlamviec.report-pdf', $data);
        $filename = 'lich-lam-viec-' . \Illuminate\Support\Str::slug($data['bacSi']->ten) . '-' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export CSV
     */
    private function exportCsv(array $data)
    {
        $filename = 'lich-lam-viec-' . \Illuminate\Support\Str::slug($data['bacSi']->ten) . '-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');

            // BOM để Excel hiển thị đúng UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header
            fputcsv($file, ['BÁO CÁO LỊCH LÀM VIỆC']);
            fputcsv($file, ['Bác sĩ:', $data['bacSi']->ten]);
            fputcsv($file, ['Chuyên khoa:', $data['bacSi']->chuyenKhoa->ten ?? 'N/A']);
            fputcsv($file, ['Từ ngày:', $data['startDate']->format('d/m/Y')]);
            fputcsv($file, ['Đến ngày:', $data['endDate']->format('d/m/Y')]);
            fputcsv($file, []);

            // Lịch làm việc định kỳ
            fputcsv($file, ['LỊCH LÀM VIỆC ĐỊNH KỲ']);
            fputcsv($file, ['Thứ', 'Giờ bắt đầu', 'Giờ kết thúc', 'Phòng']);
            foreach ($data['lichLamViecs'] as $lich) {
                $weekdays = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
                fputcsv($file, [
                    $weekdays[$lich->ngay_trong_tuan],
                    $lich->thoi_gian_bat_dau,
                    $lich->thoi_gian_ket_thuc,
                    $lich->phong->ten ?? 'N/A',
                ]);
            }
            fputcsv($file, []);

            // Lịch nghỉ
            fputcsv($file, ['LỊCH NGHỈ']);
            fputcsv($file, ['Ngày', 'Giờ bắt đầu', 'Giờ kết thúc', 'Lý do']);
            foreach ($data['lichNghis'] as $lich) {
                fputcsv($file, [
                    $lich->ngay->format('d/m/Y'),
                    $lich->bat_dau,
                    $lich->ket_thuc,
                    $lich->ly_do ?? 'N/A',
                ]);
            }
            fputcsv($file, []);

            // Ca điều chỉnh
            fputcsv($file, ['CA ĐIỀU CHỈNH']);
            fputcsv($file, ['Ngày', 'Giờ bắt đầu', 'Giờ kết thúc', 'Hành động', 'Lý do']);
            foreach ($data['caDieuChinhs'] as $ca) {
                $hanhDong = match ($ca->hanh_dong) {
                    'add' => 'Thêm',
                    'modify' => 'Sửa',
                    'cancel' => 'Hủy',
                    default => $ca->hanh_dong,
                };
                fputcsv($file, [
                    $ca->ngay->format('d/m/Y'),
                    $ca->gio_bat_dau,
                    $ca->gio_ket_thuc,
                    $hanhDong,
                    $ca->ly_do ?? 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

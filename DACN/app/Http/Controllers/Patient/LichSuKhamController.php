<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\LichHen;
use App\Models\BenhAn;
use App\Models\DonThuoc;
use App\Models\XetNghiem;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LichSuKhamController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Statistics
        $statistics = [
            'total_appointments' => LichHen::where('user_id', $userId)->count(),
            'total_medical_records' => BenhAn::where('user_id', $userId)->count(),
            'total_prescriptions' => DonThuoc::whereHas('benhAn', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })->count(),
            'total_tests' => XetNghiem::whereHas('benhAn', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })->count(),
        ];

        // Build timeline
        $timeline = [];

        // Get appointments
        $appointments = LichHen::where('user_id', $userId)
            ->with(['bacSi', 'dichVu'])
            ->orderBy('ngay_hen', 'desc')
            ->orderBy('thoi_gian_hen', 'desc')
            ->get();

        foreach ($appointments as $appointment) {
            if (!$appointment->ngay_hen) continue;

            $year = $appointment->ngay_hen->year;
            $month = $appointment->ngay_hen->month;

            try {
                $datetime = \Carbon\Carbon::parse($appointment->ngay_hen->format('Y-m-d') . ' ' . $appointment->thoi_gian_hen);
                $dateString = $datetime->format('d/m/Y H:i');
            } catch (\Exception $e) {
                $dateString = $appointment->ngay_hen->format('d/m/Y') . ' ' . $appointment->thoi_gian_hen;
            }

            $timeline[$year][$month][] = [
                'type' => 'Lịch hẹn',
                'icon' => 'calendar-check',
                'color' => '#3b82f6',
                'title' => 'Lịch hẹn khám với ' . $appointment->bacSi->ten_bac_si,
                'date' => $dateString,
                'doctor' => $appointment->bacSi->ten_bac_si,
                'description' => 'Dịch vụ: ' . ($appointment->dichVu->ten_dich_vu ?? 'N/A'),
                'action_url' => route('patient.lichhen.show', $appointment),
            ];
        }

        // Get medical records
        $benhAns = BenhAn::where('user_id', $userId)
            ->with(['bacSi'])
            ->orderBy('ngay_kham', 'desc')
            ->get();

        foreach ($benhAns as $benhAn) {
            if (!$benhAn->ngay_kham) continue;

            $year = $benhAn->ngay_kham->year;
            $month = $benhAn->ngay_kham->month;

            $timeline[$year][$month][] = [
                'type' => 'Bệnh án',
                'icon' => 'file-medical',
                'color' => '#10b981',
                'title' => 'Bệnh án #' . $benhAn->id,
                'date' => $benhAn->ngay_kham->format('d/m/Y'),
                'doctor' => $benhAn->bacSi->ten_bac_si ?? 'N/A',
                'diagnosis' => $benhAn->chan_doan,
                'action_url' => route('patient.benhan.show', $benhAn),
            ];
        }

        // Get prescriptions
        $donThuocs = DonThuoc::whereHas('benhAn', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->with(['benhAn'])
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($donThuocs as $donThuoc) {
            if (!$donThuoc->created_at) continue;

            $year = $donThuoc->created_at->year;
            $month = $donThuoc->created_at->month;

            $timeline[$year][$month][] = [
                'type' => 'Đơn thuốc',
                'icon' => 'prescription',
                'color' => '#8b5cf6',
                'title' => 'Đơn thuốc #' . $donThuoc->id,
                'date' => $donThuoc->created_at->format('d/m/Y'),
                'description' => 'Số lượng thuốc: ' . $donThuoc->items->count(),
                'action_url' => route('patient.donthuoc.index'),
            ];
        }

        // Get test results
        $xetNghiems = XetNghiem::whereHas('benhAn', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($xetNghiems as $xetNghiem) {
            if (!$xetNghiem->created_at) continue;

            $year = $xetNghiem->created_at->year;
            $month = $xetNghiem->created_at->month;

            $timeline[$year][$month][] = [
                'type' => 'Xét nghiệm',
                'icon' => 'flask',
                'color' => '#f59e0b',
                'title' => $xetNghiem->loai ?? 'Xét nghiệm',
                'date' => $xetNghiem->created_at->format('d/m/Y'),
                'description' => $xetNghiem->mo_ta,
                'action_url' => route('patient.xetnghiem.index'),
            ];
        }

        // Sort timeline
        krsort($timeline);
        foreach ($timeline as $year => &$months) {
            krsort($months);
            foreach ($months as $month => &$items) {
                usort($items, function($a, $b) {
                    return strtotime($b['date']) - strtotime($a['date']);
                });
            }
        }

        return view('patient.lich-su-kham', compact('timeline', 'statistics'));
    }
}

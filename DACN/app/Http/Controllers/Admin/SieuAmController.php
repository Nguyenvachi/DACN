<?php
// filepath: app/Http/Controllers/Admin/SieuAmController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SieuAm;
use App\Models\LoaiSieuAm;
use App\Models\BacSi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

/**
 * Controller quản lý Siêu âm cho Admin
 * Parent file: routes/web.php
 */
class SieuAmController extends Controller
{
    /**
     * Danh sách tất cả siêu âm (Admin xem toàn bộ)
     */
    public function index(Request $request)
    {
        $query = SieuAm::with(['benhAn.user', 'bacSi', 'loaiSieuAm']);

        $hasLegacyBacSiIdColumn = false;
        try {
            $hasLegacyBacSiIdColumn = Schema::hasColumn('sieu_ams', 'bac_si_id');
        } catch (\Throwable $e) {
            $hasLegacyBacSiIdColumn = false;
        }

        // Filter theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Filter theo loại
        if ($request->filled('loai_sieu_am_id')) {
            $query->where('loai_sieu_am_id', $request->loai_sieu_am_id);
        }

        // Filter theo bác sĩ
        if ($request->filled('bac_si_id')) {
            $bacSiId = $request->bac_si_id;
            $query->where(function ($q) use ($bacSiId, $hasLegacyBacSiIdColumn) {
                $q->where('bac_si_chi_dinh_id', $bacSiId);
                if ($hasLegacyBacSiIdColumn) {
                    $q->orWhere('bac_si_id', $bacSiId);
                }
            });
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('benhAn.user', function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('email', 'LIKE', '%' . $search . '%')
                  ->orWhere('so_dien_thoai', 'LIKE', '%' . $search . '%');
            });
        }

        $sieuAms = $query->orderBy('created_at', 'desc')->paginate(20);

        // Stats
        $stats = [
            'total' => SieuAm::count(),
            'pending' => SieuAm::where('trang_thai', 'pending')->count(),
            'processing' => SieuAm::where('trang_thai', 'processing')->count(),
            'completed' => SieuAm::where('trang_thai', 'completed')->count(),
        ];

        // Data cho filters
        $loaiSieuAms = LoaiSieuAm::orderBy('ten')->get();
        $bacSis = BacSi::orderBy('ho_ten')->get();

        return view('admin.sieuam.index', compact('sieuAms', 'stats', 'loaiSieuAms', 'bacSis'));
    }

    /**
     * Chi tiết siêu âm
     */
    public function show(SieuAm $sieuAm)
    {
        $sieuAm->load(['benhAn.user', 'benhAn.lichHen', 'bacSi.user', 'loaiSieuAm']);
        return view('admin.sieuam.show', compact('sieuAm'));
    }

    /**
     * Xóa siêu âm (Admin only)
     */
    public function destroy(SieuAm $sieuAm)
    {
        // Xóa file
        if ($sieuAm->file_path) {
            Storage::disk($sieuAm->disk)->delete($sieuAm->file_path);
        }

        $sieuAm->delete();

        return redirect()
            ->route('admin.sieuam.index')
            ->with('success', 'Đã xóa siêu âm #SA' . $sieuAm->id);
    }

    /**
     * Download file siêu âm
     */
    public function download(SieuAm $sieuAm)
    {
        if (!$sieuAm->file_path) {
            abort(404, 'File không tồn tại');
        }

        if (!Storage::disk($sieuAm->disk)->exists($sieuAm->file_path)) {
            abort(404, 'File không tồn tại trên server');
        }

        return Storage::disk($sieuAm->disk)->download($sieuAm->file_path);
    }

    /**
     * Trang thống kê và báo cáo (parity với Xét nghiệm)
     */
    public function statistics(Request $request)
    {
        $hasLegacyBacSiIdColumn = false;
        try {
            $hasLegacyBacSiIdColumn = Schema::hasColumn('sieu_ams', 'bac_si_id');
        } catch (\Throwable $e) {
            $hasLegacyBacSiIdColumn = false;
        }

        // Thống kê theo tháng (12 tháng gần nhất)
        $monthlyStats = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyStats[] = [
                'month' => $date->format('m/Y'),
                'total' => SieuAm::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'completed' => SieuAm::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->completed()
                    ->count(),
            ];
        }

        // Top bác sĩ chỉ định nhiều nhất
        $doctorIdExpr = $hasLegacyBacSiIdColumn
            ? DB::raw('COALESCE(sieu_ams.bac_si_chi_dinh_id, sieu_ams.bac_si_id)')
            : 'sieu_ams.bac_si_chi_dinh_id';

        $topDoctors = DB::table('sieu_ams')
            ->join('bac_sis', 'bac_sis.id', '=', $doctorIdExpr)
            ->join('users', 'bac_sis.user_id', '=', 'users.id')
            ->select('bac_sis.id', 'users.name', DB::raw('count(*) as total'))
            ->groupBy('bac_sis.id', 'users.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Top loại siêu âm phổ biến
        $topTypes = DB::table('sieu_ams')
            ->join('loai_sieu_ams', 'sieu_ams.loai_sieu_am_id', '=', 'loai_sieu_ams.id')
            ->select('loai_sieu_ams.ten as loai', DB::raw('count(*) as total'))
            ->groupBy('loai_sieu_ams.ten')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Thống kê theo trạng thái
        $statusStats = [
            'pending' => SieuAm::pending()->count(),
            'processing' => SieuAm::processing()->count(),
            'completed' => SieuAm::completed()->count(),
        ];

        // Thời gian xử lý trung bình (giờ)
        $avgProcessingTime = SieuAm::completed()
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, COALESCE(ngay_hoan_thanh, updated_at))) as avg_hours')
            ->value('avg_hours');

        return view('admin.sieuam.statistics', compact(
            'monthlyStats',
            'topDoctors',
            'topTypes',
            'statusStats',
            'avgProcessingTime'
        ));
    }

    /**
     * Báo cáo thống kê siêu âm
     */
    public function report(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        $hasLegacyBacSiIdColumn = false;
        try {
            $hasLegacyBacSiIdColumn = Schema::hasColumn('sieu_ams', 'bac_si_id');
        } catch (\Throwable $e) {
            $hasLegacyBacSiIdColumn = false;
        }

        // Thống kê theo loại siêu âm
        $byType = SieuAm::selectRaw('loai, COUNT(*) as total, SUM(gia) as revenue')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('loai')
            ->orderByDesc('total')
            ->get();

        // Thống kê theo bác sĩ
        if ($hasLegacyBacSiIdColumn) {
            $byDoctor = SieuAm::with('bacSi')
                ->selectRaw('COALESCE(bac_si_chi_dinh_id, bac_si_id) as bac_si_chi_dinh_id, COUNT(*) as total')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy(DB::raw('COALESCE(bac_si_chi_dinh_id, bac_si_id)'))
                ->orderByDesc('total')
                ->get();
        } else {
            $byDoctor = SieuAm::with('bacSi')
                ->selectRaw('bac_si_chi_dinh_id, COUNT(*) as total')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('bac_si_chi_dinh_id')
                ->orderByDesc('total')
                ->get();
        }

        // Thống kê theo trạng thái
        $byStatus = SieuAm::selectRaw('trang_thai, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('trang_thai')
            ->get();

        return view('admin.sieuam.report', compact('byType', 'byDoctor', 'byStatus', 'startDate', 'endDate'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\XetNghiem;
use App\Models\BacSi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Controller quản trị Xét nghiệm - Admin
 * Chức năng: Xem tất cả, thống kê, báo cáo
 */
class XetNghiemController extends Controller
{
    /**
     * Danh sách tất cả xét nghiệm trong hệ thống
     */
    public function index(Request $request)
    {
        $query = XetNghiem::with(['benhAn.user', 'bacSi.user']);

        // Filter theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Filter theo bác sĩ
        if ($request->filled('bac_si_id')) {
            $query->where('bac_si_id', $request->bac_si_id);
        }

        // Filter theo loại
        if ($request->filled('loai')) {
            $query->where('loai', 'like', '%' . $request->loai . '%');
        }

        // Filter theo ngày
        if ($request->filled('tu_ngay')) {
            $query->whereDate('created_at', '>=', $request->tu_ngay);
        }
        if ($request->filled('den_ngay')) {
            $query->whereDate('created_at', '<=', $request->den_ngay);
        }

        // Search theo tên bệnh nhân
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('benhAn.user', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        $xetNghiems = $query->orderBy('created_at', 'desc')->paginate(20);

        // Danh sách bác sĩ cho filter
        $bacSis = BacSi::with('user')->get();

        // Danh sách loại xét nghiệm
        $loaiXetNghiem = XetNghiem::select('loai')
            ->distinct()
            ->pluck('loai');

        // Thống kê tổng quan
        $stats = [
            'total' => XetNghiem::count(),
            'pending' => XetNghiem::pending()->count(),
            'processing' => XetNghiem::processing()->count(),
            'completed' => XetNghiem::completed()->count(),
            'today' => XetNghiem::whereDate('created_at', Carbon::today())->count(),
            'this_week' => XetNghiem::whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])->count(),
            'this_month' => XetNghiem::whereMonth('created_at', Carbon::now()->month)->count(),
        ];

        return view('admin.xetnghiem.index', compact(
            'xetNghiems',
            'bacSis',
            'loaiXetNghiem',
            'stats'
        ));
    }

    /**
     * Chi tiết xét nghiệm
     */
    public function show(XetNghiem $xetNghiem)
    {
        $xetNghiem->load([
            'benhAn.user',
            'benhAn.lichHen',
            'bacSi.user'
        ]);

        return view('admin.xetnghiem.show', compact('xetNghiem'));
    }

    /**
     * Trang thống kê và báo cáo
     */
    public function statistics(Request $request)
    {
        // Thống kê theo tháng (12 tháng gần nhất)
        $monthlyStats = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyStats[] = [
                'month' => $date->format('m/Y'),
                'total' => XetNghiem::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'completed' => XetNghiem::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->completed()
                    ->count(),
            ];
        }

        // Top bác sĩ chỉ định nhiều nhất
        $topDoctors = DB::table('xet_nghiems')
            ->join('bac_sis', 'xet_nghiems.bac_si_id', '=', 'bac_sis.id')
            ->join('users', 'bac_sis.user_id', '=', 'users.id')
            ->select('bac_sis.id', 'users.name', DB::raw('count(*) as total'))
            ->groupBy('bac_sis.id', 'users.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Top loại xét nghiệm phổ biến
        $topTypes = XetNghiem::select('loai', DB::raw('count(*) as total'))
            ->groupBy('loai')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Thống kê theo trạng thái
        $statusStats = [
            'pending' => XetNghiem::pending()->count(),
            'processing' => XetNghiem::processing()->count(),
            'completed' => XetNghiem::completed()->count(),
        ];

        // Thời gian xử lý trung bình
        $avgProcessingTime = XetNghiem::completed()
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours')
            ->value('avg_hours');

        return view('admin.xetnghiem.statistics', compact(
            'monthlyStats',
            'topDoctors',
            'topTypes',
            'statusStats',
            'avgProcessingTime'
        ));
    }

    /**
     * Xuất báo cáo Excel (TODO)
     */
    public function export(Request $request)
    {
        // TODO: Implement Excel export
        return back()->with('info', 'Chức năng xuất báo cáo đang phát triển');
    }
}

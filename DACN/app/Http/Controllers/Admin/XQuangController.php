<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BacSi;
use App\Models\XQuang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Controller quản trị X-Quang - Admin
 * Chức năng: Xem tất cả, thống kê, báo cáo
 */
class XQuangController extends Controller
{
    public function index(Request $request)
    {
        $query = XQuang::with(['benhAn.user', 'bacSi.user']);

        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        if ($request->filled('bac_si_id')) {
            $query->where('bac_si_id', $request->bac_si_id);
        }

        if ($request->filled('loai')) {
            $query->where('loai', 'like', '%' . $request->loai . '%');
        }

        if ($request->filled('tu_ngay')) {
            $query->whereDate('created_at', '>=', $request->tu_ngay);
        }
        if ($request->filled('den_ngay')) {
            $query->whereDate('created_at', '<=', $request->den_ngay);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('benhAn.user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        $xQuangs = $query->orderBy('created_at', 'desc')->paginate(20);

        $bacSis = BacSi::with('user')->get();

        $loaiXQuang = XQuang::select('loai')->distinct()->pluck('loai');

        $stats = [
            'total' => XQuang::count(),
            'pending' => XQuang::pending()->count(),
            'processing' => XQuang::processing()->count(),
            'completed' => XQuang::completed()->count(),
            'today' => XQuang::whereDate('created_at', Carbon::today())->count(),
            'this_week' => XQuang::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
            'this_month' => XQuang::whereMonth('created_at', Carbon::now()->month)->count(),
        ];

        return view('admin.xquang.index', compact('xQuangs', 'bacSis', 'loaiXQuang', 'stats'));
    }

    public function show(XQuang $xQuang)
    {
        $xQuang->load(['benhAn.user', 'benhAn.lichHen', 'bacSi.user']);

        return view('admin.xquang.show', compact('xQuang'));
    }

    public function statistics(Request $request)
    {
        $monthlyStats = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyStats[] = [
                'month' => $date->format('m/Y'),
                'total' => XQuang::whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->count(),
                'completed' => XQuang::whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->completed()->count(),
            ];
        }

        $topDoctors = DB::table('x_quangs')
            ->join('bac_sis', 'x_quangs.bac_si_id', '=', 'bac_sis.id')
            ->join('users', 'bac_sis.user_id', '=', 'users.id')
            ->select('bac_sis.id', 'users.name', DB::raw('count(*) as total'))
            ->groupBy('bac_sis.id', 'users.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $topTypes = XQuang::select('loai', DB::raw('count(*) as total'))
            ->groupBy('loai')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $statusStats = [
            'pending' => XQuang::pending()->count(),
            'processing' => XQuang::processing()->count(),
            'completed' => XQuang::completed()->count(),
        ];

        $avgProcessingTime = XQuang::completed()
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours')
            ->value('avg_hours');

        return view('admin.xquang.statistics', compact('monthlyStats', 'topDoctors', 'topTypes', 'statusStats', 'avgProcessingTime'));
    }

    public function export(Request $request)
    {
        return back()->with('info', 'Chức năng xuất báo cáo đang phát triển');
    }
}

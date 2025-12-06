<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\HoaDon;
use Illuminate\Http\Request;

class HoaDonController extends Controller
{
    public function index(Request $request)
    {
        $query = HoaDon::where('user_id', auth()->id())
            ->with(['lichHen.bacSi', 'lichHen.dichVu'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('trang_thai', $request->status);
        }

        $hoaDons = $query->paginate(15);

        // Statistics
        $statistics = [
            'total' => HoaDon::where('user_id', auth()->id())->count(),
            'unpaid' => HoaDon::where('user_id', auth()->id())
                ->whereIn('trang_thai', ['chua_thanh_toan', 'thanh_toan_mot_phan'])
                ->count(),
            'paid' => HoaDon::where('user_id', auth()->id())
                ->where('trang_thai', 'da_thanh_toan')
                ->count(),
            'total_amount' => HoaDon::where('user_id', auth()->id())
                ->where('trang_thai', 'da_thanh_toan')
                ->sum('tong_tien'),
        ];

        return view('patient.hoadon.index', compact('hoaDons', 'statistics'));
    }

    public function show(HoaDon $hoaDon)
    {
        abort_if($hoaDon->user_id !== auth()->id(), 403);

        $hoaDon->load(['lichHen.bacSi', 'lichHen.dichVu', 'thanhToans', 'hoanTiens', 'paymentLogs']);

        return view('patient.hoadon.show', compact('hoaDon'));
    }
}


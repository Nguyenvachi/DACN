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
            $status = $request->status;
            $englishStatuses = ['unpaid', 'paid', 'partial', 'partial_refund', 'refunded', 'cancelled'];
            if (in_array($status, $englishStatuses, true)) {
                $query->where('status', $status);
            } else {
                $query->where('trang_thai', $status);
            }
        }

        $hoaDons = $query->paginate(15);

        // Statistics
        // Use `status` column (english keys) for reliable statistics
        $statistics = [
            'total' => HoaDon::where('user_id', auth()->id())->count(),
            'unpaid' => HoaDon::where('user_id', auth()->id())
                ->where('status', 'unpaid')
                ->count(),
            'paid' => HoaDon::where('user_id', auth()->id())
                ->where('status', 'paid')
                ->count(),
            'total_amount' => HoaDon::where('user_id', auth()->id())
                ->where('status', 'paid')
                ->sum('tong_tien'),
        ];

        return view('patient.hoadon.index', compact('hoaDons', 'statistics'));
    }

    public function show(HoaDon $hoaDon)
    {
        abort_if($hoaDon->user_id !== auth()->id(), 403);

        // Refresh model from DB to ensure we have latest computed fields (paid/refund amounts)
        $hoaDon->refresh();
        $hoaDon->load(['lichHen.bacSi', 'lichHen.dichVu', 'thanhToans', 'hoanTiens', 'paymentLogs']);

        return view('patient.hoadon.show', compact('hoaDon'));
    }
}


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

        // Statistics - Tính toán chính xác dựa trên số tiền
        $allHoaDons = HoaDon::where('user_id', auth()->id())->get();

        $statistics = [
            'total' => $allHoaDons->count(),
            'unpaid' => $allHoaDons->filter(function ($hd) {
                return $hd->so_tien_con_lai > 0;
            })->count(),
            'paid' => $allHoaDons->filter(function ($hd) {
                return $hd->so_tien_con_lai == 0 && $hd->tong_tien > 0;
            })->count(),
            'total_amount' => $allHoaDons->sum('tong_tien'),
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

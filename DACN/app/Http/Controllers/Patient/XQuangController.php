<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\XQuang;
use Illuminate\Http\Request;

class XQuangController extends Controller
{
    /**
     * Danh sách X-Quang của bệnh nhân
     * Đồng bộ filter/search theo module Xét nghiệm
     */
    public function index(Request $request)
    {
        $query = XQuang::with(['benhAn.bacSi.user', 'benhAn.bacSi.chuyenKhoa'])
            ->whereHas('benhAn', function ($q) {
                $q->where('user_id', auth()->id());
            });

        if ($request->filled('status')) {
            $query->where('trang_thai', $request->status);
        }

        if ($request->filled('loai')) {
            $query->where('loai', 'LIKE', '%' . $request->loai . '%');
        }

        // Filter theo ngày chỉ định (đồng bộ với Xét nghiệm)
        if ($request->filled('from_date')) {
            $query->whereDate('ngay_chi_dinh', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('ngay_chi_dinh', '<=', $request->to_date);
        }

        // Tìm kiếm theo bác sĩ (đồng bộ với Xét nghiệm)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('bacSi.user', function ($q) use ($search) {
                $q->where('ho_ten', 'LIKE', '%' . $search . '%');
            });
        }

        $xQuangs = $query->orderBy('created_at', 'desc')->paginate(10);

        $stats = [
            'total' => XQuang::byPatient(auth()->id())->count(),
            'pending' => XQuang::byPatient(auth()->id())->pending()->count(),
            'processing' => XQuang::byPatient(auth()->id())->processing()->count(),
            'completed' => XQuang::byPatient(auth()->id())->completed()->count(),
        ];

        $loaiXQuangs = XQuang::byPatient(auth()->id())
            ->distinct()
            ->pluck('loai')
            ->filter()
            ->sort();

        return view('patient.xquang.index', compact('xQuangs', 'stats', 'loaiXQuangs'));
    }

    public function show(XQuang $xQuang)
    {
        if ($xQuang->benhAn?->user_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền xem X-Quang này');
        }

        $xQuang->load(['benhAn.bacSi.user', 'benhAn.bacSi.chuyenKhoa', 'benhAn.lichHen']);

        return view('patient.xquang.show', compact('xQuang'));
    }
}

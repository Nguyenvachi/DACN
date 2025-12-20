<?php
// Parent file: app/Http/Controllers/Patient/XetNghiemController.php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\XetNghiem;
use Illuminate\Http\Request;

class XetNghiemController extends Controller
{
    /**
     * Danh sách xét nghiệm của bệnh nhân
     */
    public function index(Request $request)
    {
        // Query cơ bản
        $query = XetNghiem::with(['benhAn.bacSi.user', 'benhAn.bacSi.chuyenKhoa'])
            ->whereHas('benhAn', function($q) {
                $q->where('user_id', auth()->id());
            });

        // Filter theo trạng thái
        if ($request->filled('status')) {
            $query->where('trang_thai', $request->status);
        }

        // Filter theo loại xét nghiệm
        if ($request->filled('loai')) {
            $query->where('loai', 'LIKE', '%' . $request->loai . '%');
        }

        // Filter theo ngày
        if ($request->filled('from_date')) {
            $query->whereDate('ngay_chi_dinh', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('ngay_chi_dinh', '<=', $request->to_date);
        }

        // Tìm kiếm theo bác sĩ
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('bacSi.user', function($q) use ($search) {
                $q->where('ho_ten', 'LIKE', '%' . $search . '%');
            });
        }

        $xetNghiems = $query->orderBy('created_at', 'desc')->paginate(10);

        // Thống kê
        $stats = [
            'total' => XetNghiem::whereHas('benhAn', function($q) {
                $q->where('user_id', auth()->id());
            })->count(),
            'pending' => XetNghiem::whereHas('benhAn', function($q) {
                $q->where('user_id', auth()->id());
            })->pending()->count(),
            'processing' => XetNghiem::whereHas('benhAn', function($q) {
                $q->where('user_id', auth()->id());
            })->where('trang_thai', 'processing')->count(),
            'completed' => XetNghiem::whereHas('benhAn', function($q) {
                $q->where('user_id', auth()->id());
            })->completed()->count(),
        ];

        // Các loại xét nghiệm (cho filter)
        $loaiXetNghiems = XetNghiem::whereHas('benhAn', function($q) {
                $q->where('user_id', auth()->id());
            })
            ->distinct()
            ->pluck('loai')
            ->filter()
            ->sort();

        return view('patient.xetnghiem.index', compact('xetNghiems', 'stats', 'loaiXetNghiems'));
    }

    /**
     * Chi tiết xét nghiệm
     */
    public function show(XetNghiem $xetNghiem)
    {
        // Check authorization
        if ($xetNghiem->benhAn->user_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền xem xét nghiệm này');
        }

        $xetNghiem->load(['benhAn.bacSi.user', 'benhAn.bacSi.chuyenKhoa', 'benhAn.lichHen']);

        return view('patient.xetnghiem.show', compact('xetNghiem'));
    }
}

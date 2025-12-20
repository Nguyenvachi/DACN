<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\NoiSoi;
use Illuminate\Http\Request;

class NoiSoiController extends Controller
{
    /**
     * Danh sách Nội soi của bệnh nhân
     */
    public function index(Request $request)
    {
        $query = NoiSoi::with(['benhAn.bacSi.user', 'benhAn.bacSi.chuyenKhoa'])
            ->where('user_id', auth()->id());
        $query->with('loaiNoiSoi');

        if ($request->filled('status')) {
            $query->where('trang_thai', $request->status);
        }

        if ($request->filled('loai')) {
            $query->where('loai', 'LIKE', '%' . $request->loai . '%');
        }

        if ($request->filled('from_date')) {
            $query->whereDate('ngay_chi_dinh', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('ngay_chi_dinh', '<=', $request->to_date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('benhAn.bacSi.user', function ($q) use ($search) {
                $q->where('ho_ten', 'LIKE', '%' . $search . '%')
                    ->orWhere('name', 'LIKE', '%' . $search . '%');
            });
        }

        $noiSois = $query->orderBy('created_at', 'desc')->paginate(10);

        $stats = [
            'total' => NoiSoi::byPatient(auth()->id())->count(),
            'pending' => NoiSoi::byPatient(auth()->id())->pending()->count(),
            'processing' => NoiSoi::byPatient(auth()->id())->processing()->count(),
            'completed' => NoiSoi::byPatient(auth()->id())->completed()->count(),
        ];

        $loaiNoiSois = NoiSoi::byPatient(auth()->id())
            ->distinct()
            ->pluck('loai')
            ->filter()
            ->sort();

        return view('patient.noisoi.index', compact('noiSois', 'stats', 'loaiNoiSois'));
    }

    public function show(NoiSoi $noiSoi)
    {
        if ((int) $noiSoi->user_id !== (int) auth()->id()) {
            abort(403, 'Bạn không có quyền xem Nội soi này');
        }

        $noiSoi->load(['benhAn.bacSi.user', 'benhAn.bacSi.chuyenKhoa', 'benhAn.lichHen', 'loaiNoiSoi']);

        return view('patient.noisoi.show', compact('noiSoi'));
    }
}

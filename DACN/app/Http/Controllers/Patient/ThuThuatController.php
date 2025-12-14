<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\ThuThuat;
use Illuminate\Http\Request;

class ThuThuatController extends Controller
{
    public function index()
    {
        $thuThuats = ThuThuat::whereHas('benhAn', function ($q) {
            $q->where('user_id', auth()->id());
        })
            ->with(['benhAn', 'loaiThuThuat', 'bacSiThucHien.user'])
            ->orderBy('ngay_thuc_hien', 'desc')
            ->paginate(10);

        return view('patient.thuthuat.index', compact('thuThuats'));
    }

    public function show(ThuThuat $thuThuat)
    {
        // Check quyền
        if ($thuThuat->benhAn->user_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền xem kết quả thủ thuật này.');
        }

        $thuThuat->load(['benhAn', 'loaiThuThuat', 'bacSiThucHien.user']);

        return view('patient.thuthuat.show', compact('thuThuat'));
    }
}

<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\SieuAm;
use Illuminate\Http\Request;

class SieuAmController extends Controller
{
    public function index()
    {
        $sieuAms = SieuAm::whereHas('benhAn', function ($q) {
            $q->where('user_id', auth()->id());
        })
            ->with(['benhAn', 'loaiSieuAm', 'bacSiThucHien.user'])
            ->orderBy('ngay_thuc_hien', 'desc')
            ->paginate(10);

        return view('patient.sieuam.index', compact('sieuAms'));
    }

    public function show(SieuAm $sieuAm)
    {
        // Check quyền
        if ($sieuAm->benhAn->user_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền xem kết quả siêu âm này.');
        }

        $sieuAm->load(['benhAn', 'loaiSieuAm', 'bacSiThucHien.user']);

        return view('patient.sieuam.show', compact('sieuAm'));
    }
}

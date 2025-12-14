<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\XetNghiem;
use Illuminate\Http\Request;

class XetNghiemController extends Controller
{
    public function index()
    {
        $xetNghiems = XetNghiem::with(['benhAn.bacSi', 'bacSiChiDinh'])
            ->whereHas('benhAn', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('patient.xetnghiem.index', compact('xetNghiems'));
    }

    public function show(XetNghiem $xetNghiem)
    {
        // Check authorization
        if ($xetNghiem->benhAn->user_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền xem xét nghiệm này');
        }

        $xetNghiem->load(['benhAn.bacSi', 'bacSiChiDinh']);

        return view('patient.xetnghiem.show', compact('xetNghiem'));
    }
}

<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\DonThuoc;
use Illuminate\Http\Request;

class DonThuocController extends Controller
{
    public function index()
    {
        $donThuocs = DonThuoc::with(['benhAn.bacSi', 'items.thuoc'])
            ->whereHas('benhAn', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('patient.donthuoc.index', compact('donThuocs'));
    }

    public function show(DonThuoc $donThuoc)
    {
        // Check authorization
        if ($donThuoc->benhAn->user_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền xem đơn thuốc này');
        }

        $donThuoc->load(['benhAn.bacSi', 'items.thuoc']);

        return view('patient.donthuoc.show', compact('donThuoc'));
    }
}

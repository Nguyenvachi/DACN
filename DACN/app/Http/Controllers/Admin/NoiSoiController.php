<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NoiSoi;
use Illuminate\Http\Request;

class NoiSoiController extends Controller
{
    public function index(Request $request)
    {
        $query = NoiSoi::query()->with(['benhAn.user', 'bacSiChiDinh.user', 'loaiNoiSoi']);

        $noiSois = $query->orderByDesc('created_at')->get();

        return view('admin.noisoi.index', compact('noiSois'));
    }

    public function show(NoiSoi $noiSoi)
    {
        $noiSoi->load(['benhAn.user', 'benhAn.lichHen', 'bacSiChiDinh.user', 'loaiNoiSoi']);

        return view('admin.noisoi.show', compact('noiSoi'));
    }
}

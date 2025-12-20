<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TheoDoiThaiKy;
use Illuminate\Http\Request;

class TheoDoiThaiKyController extends Controller
{
    public function index(Request $request)
    {
        $query = TheoDoiThaiKy::with(['benhAn.user', 'benhAn.bacSi.user', 'benhAn.lichHen'])
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('trang_thai', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', '%' . $search . '%')
                    ->orWhereHas('benhAn.user', function ($u) use ($search) {
                        $u->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%')
                            ->orWhere('so_dien_thoai', 'like', '%' . $search . '%')
                            ->orWhere('ho_ten', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('benhAn.bacSi.user', function ($u) use ($search) {
                        $u->where('ho_ten', 'like', '%' . $search . '%')
                            ->orWhere('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $records = $query->paginate(30);

        $stats = [
            'total' => TheoDoiThaiKy::count(),
            'submitted' => TheoDoiThaiKy::where('trang_thai', TheoDoiThaiKy::STATUS_SUBMITTED)->count(),
            'reviewed' => TheoDoiThaiKy::where('trang_thai', TheoDoiThaiKy::STATUS_REVIEWED)->count(),
            'recorded' => TheoDoiThaiKy::where('trang_thai', TheoDoiThaiKy::STATUS_RECORDED)->count(),
            'archived' => TheoDoiThaiKy::where('trang_thai', TheoDoiThaiKy::STATUS_ARCHIVED)->count(),
        ];

        return view('admin.theodoithaiky.index', compact('records', 'stats'));
    }

    public function show(TheoDoiThaiKy $theoDoiThaiKy)
    {
        $theoDoiThaiKy->loadMissing(['benhAn.user', 'benhAn.bacSi.user', 'benhAn.lichHen']);
        $this->authorize('view', $theoDoiThaiKy);

        return view('admin.theodoithaiky.show', ['record' => $theoDoiThaiKy]);
    }
}

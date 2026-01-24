<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaiKham;
use Illuminate\Http\Request;

class TaiKhamController extends Controller
{
    public function index(Request $request)
    {
        $query = TaiKham::with(['benhAn.user', 'benhAn.bacSi.user', 'lichHen'])
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
                            ->orWhere('ho_ten', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%')
                            ->orWhere('so_dien_thoai', 'like', '%' . $search . '%');
                    });
            });
        }

        $records = $query->paginate(20);

        $stats = [
            'pending' => TaiKham::where('trang_thai', TaiKham::STATUS_PENDING_VN)->count(),
            'confirmed' => TaiKham::where('trang_thai', TaiKham::STATUS_CONFIRMED_VN)->count(),
            'booked' => TaiKham::where('trang_thai', TaiKham::STATUS_BOOKED_VN)->count(),
            'completed' => TaiKham::where('trang_thai', TaiKham::STATUS_COMPLETED_VN)->count(),
            'cancelled' => TaiKham::where('trang_thai', TaiKham::STATUS_CANCELLED_VN)->count(),
        ];

        return view('admin.taikham.index', compact('records', 'stats'));
    }

    public function show(TaiKham $taiKham)
    {
        $taiKham->loadMissing(['benhAn.user', 'benhAn.bacSi.user', 'lichHen']);
        $this->authorize('view', $taiKham);

        return view('admin.taikham.show', ['record' => $taiKham]);
    }
}

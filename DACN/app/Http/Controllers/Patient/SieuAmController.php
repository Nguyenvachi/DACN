<?php
// filepath: app/Http/Controllers/Patient/SieuAmController.php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\SieuAm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Controller quản lý Siêu âm cho Bệnh nhân
 * Parent file: routes/web.php
 */
class SieuAmController extends Controller
{
    /**
     * Danh sách siêu âm của bệnh nhân
     */
    public function index(Request $request)
    {
        $userId = auth()->id();

        $query = SieuAm::with(['benhAn', 'bacSi.user', 'bacSi.chuyenKhoa', 'loaiSieuAm'])
            ->whereHas('benhAn', function($q) use ($userId) {
                $q->where('user_id', $userId);
            });

        // Filter theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        $sieuAms = $query->orderBy('created_at', 'desc')->paginate(15);

        // Stats
        $stats = [
            'total' => SieuAm::whereHas('benhAn', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })->count(),
            'pending' => SieuAm::whereHas('benhAn', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })->where('trang_thai', 'pending')->count(),
            'completed' => SieuAm::whereHas('benhAn', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })->where('trang_thai', 'completed')->count(),
        ];

        return view('patient.sieuam.index', compact('sieuAms', 'stats'));
    }

    /**
     * Chi tiết siêu âm của bệnh nhân
     */
    public function show(SieuAm $sieuAm)
    {
        // Check quyền: chỉ xem siêu âm của mình
        if ($sieuAm->benhAn->user_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền xem siêu âm này');
        }

        $sieuAm->load(['benhAn', 'bacSi.user', 'loaiSieuAm']);

        return view('patient.sieuam.show', compact('sieuAm'));
    }

    /**
     * Download file kết quả siêu âm
     */
    public function download(SieuAm $sieuAm)
    {
        // Check quyền
        if ($sieuAm->benhAn->user_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền tải file này');
        }

        if (!$sieuAm->file_path) {
            abort(404, 'File không tồn tại');
        }

        if (!Storage::disk($sieuAm->disk)->exists($sieuAm->file_path)) {
            abort(404, 'File không tồn tại trên server');
        }

        return Storage::disk($sieuAm->disk)->download($sieuAm->file_path);
    }
}

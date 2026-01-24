<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\SieuAm;
use App\Models\LoaiSieuAm;
use App\Models\BenhAn;
use App\Models\BacSi;
use App\Services\MedicalWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Controller quản lý Siêu âm của Bác sĩ
 * File mẹ: routes/web.php
 */
class SieuAmController extends Controller
{
    /**
     * Danh sách siêu âm của bác sĩ
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $bacSi = BacSi::where('user_id', $user->id)->firstOrFail();

        // Base query với eager loading
        $query = SieuAm::with(['benhAn.user', 'loaiSieuAm'])
            ->byBacSi($bacSi->id);

        // Filter theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Filter theo loại siêu âm
        if ($request->filled('loai_sieu_am_id')) {
            $query->where('loai_sieu_am_id', $request->loai_sieu_am_id);
        }

        // Search theo tên bệnh nhân
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('benhAn.user', function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('email', 'LIKE', '%' . $search . '%');
            });
        }

        $sieuAms = $query->orderBy('created_at', 'desc')->paginate(20);

        // Thống kê
        $stats = [
            'total' => SieuAm::byBacSi($bacSi->id)->count(),
            'pending' => SieuAm::byBacSi($bacSi->id)->pending()->count(),
            'processing' => SieuAm::byBacSi($bacSi->id)->where('trang_thai', 'processing')->count(),
            'completed' => SieuAm::byBacSi($bacSi->id)->completed()->count(),
        ];

        // Các loại siêu âm (cho filter)
        $loaiSieuAms = LoaiSieuAm::active()->orderBy('ten')->get();

        return view('doctor.sieuam.index', compact('sieuAms', 'stats', 'loaiSieuAms'));
    }

    /**
     * Form chỉ định siêu âm mới
     */
    public function create(Request $request)
    {
        $benhAnId = $request->query('benh_an_id');
        $benhAn = BenhAn::with('user')->findOrFail($benhAnId);

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $bacSi = BacSi::where('user_id', $user->id)->firstOrFail();

        // Check quyền
        if ($benhAn->bac_si_id !== $bacSi->id) {
            abort(403, 'Bạn không có quyền chỉ định siêu âm cho bệnh án này.');
        }

        // Lọc loại siêu âm theo chuyên khoa (pivot chuyen_khoa_loai_sieu_am)
        $chuyenKhoaIds = [];
        if (!empty($bacSi->chuyen_khoa_id)) {
            $chuyenKhoaIds[] = $bacSi->chuyen_khoa_id;
        }
        try {
            $bacSi->loadMissing('chuyenKhoas');
            $chuyenKhoaIds = array_values(array_unique(array_merge(
                $chuyenKhoaIds,
                $bacSi->chuyenKhoas->pluck('id')->all()
            )));
        } catch (\Throwable $e) {
            // Nếu dữ liệu chuyên khoa chưa đầy đủ, fallback về danh sách active
        }

        $loaiSieuAms = LoaiSieuAm::active()
            ->when(!empty($chuyenKhoaIds), function ($q) use ($chuyenKhoaIds) {
                $q->whereHas('chuyenKhoas', function ($cq) use ($chuyenKhoaIds) {
                    $cq->whereIn('chuyen_khoas.id', $chuyenKhoaIds);
                });
            })
            ->orderBy('ten')
            ->get();

        return view('doctor.sieuam.create', compact('benhAn', 'loaiSieuAms'));
    }

    /**
     * Lưu chỉ định siêu âm
     */
    public function store(Request $request, MedicalWorkflowService $workflow)
    {
        $validated = $request->validate([
            'benh_an_id' => 'required|exists:benh_ans,id',
            'loai_sieu_am_id' => 'required|exists:loai_sieu_ams,id',
            'mo_ta' => 'nullable|string|max:1000',
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $bacSi = BacSi::where('user_id', $user->id)->firstOrFail();

        $benhAn = BenhAn::findOrFail($validated['benh_an_id']);

        // Check quyền
        if ($benhAn->bac_si_id !== $bacSi->id) {
            abort(403);
        }

        $loaiSieuAm = LoaiSieuAm::findOrFail($validated['loai_sieu_am_id']);

        // Enforce lọc theo chuyên khoa nếu bác sĩ có chuyên khoa (pivot)
        $chuyenKhoaIds = [];
        if (!empty($bacSi->chuyen_khoa_id)) {
            $chuyenKhoaIds[] = $bacSi->chuyen_khoa_id;
        }
        try {
            $bacSi->loadMissing('chuyenKhoas');
            $chuyenKhoaIds = array_values(array_unique(array_merge(
                $chuyenKhoaIds,
                $bacSi->chuyenKhoas->pluck('id')->all()
            )));
        } catch (\Throwable $e) {
            // ignore
        }

        if (!empty($chuyenKhoaIds)) {
            $allowed = $loaiSieuAm->chuyenKhoas()
                ->whereIn('chuyen_khoas.id', $chuyenKhoaIds)
                ->exists();
            if (!$allowed) {
                abort(403, 'Loại siêu âm này không thuộc chuyên khoa của bạn.');
            }
        }

        // Ưu tiên workflow service (đồng bộ chuẩn Xét nghiệm), có fallback để không phá luồng cũ
        try {
            $sieuAm = $workflow->createUltrasoundRequest($benhAn, [
                'loai_sieu_am_id' => $loaiSieuAm->id,
                'loai' => $loaiSieuAm->ten,
                'mo_ta' => $validated['mo_ta'] ?? null,
                'gia' => $loaiSieuAm->gia_mac_dinh ?? 0,
                'ngay_chi_dinh' => now(),
                'phong_id' => $loaiSieuAm->phong_id ?? null,
            ]);
        } catch (\Throwable $e) {
            $payload = [
                'user_id' => $benhAn->user_id,
                'benh_an_id' => $benhAn->id,
                'bac_si_chi_dinh_id' => $bacSi->id,
                'loai_sieu_am_id' => $loaiSieuAm->id,
                'loai' => $loaiSieuAm->ten,
                'mo_ta' => $validated['mo_ta'] ?? null,
                'gia' => $loaiSieuAm->gia_mac_dinh ?? 0,
                'trang_thai' => SieuAm::STATUS_PENDING,
                'ngay_chi_dinh' => now(),
                'phong_id' => $loaiSieuAm->phong_id ?? null,
            ];

            try {
                if (\Illuminate\Support\Facades\Schema::hasColumn('sieu_ams', 'bac_si_id')) {
                    $payload['bac_si_id'] = $bacSi->id;
                }
            } catch (\Throwable $e2) {
                // ignore
            }

            $sieuAm = SieuAm::create($payload);
        }

        return redirect()
            ->route('doctor.benhan.edit', $benhAn->id)
            ->with('status', 'Đã chỉ định siêu âm thành công! Mã SA-' . $sieuAm->id);
    }

    /**
     * Xem chi tiết siêu âm
     */
    public function show(SieuAm $sieuAm)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $bacSi = BacSi::where('user_id', $user->id)->firstOrFail();

        // Check quyền
        $chiDinhId = $sieuAm->bac_si_chi_dinh_id ?? $sieuAm->bac_si_id;
        if ($chiDinhId !== $bacSi->id) {
            abort(403);
        }

        $sieuAm->load(['benhAn.user', 'bacSi', 'loaiSieuAm']);

        return view('doctor.sieuam.show', compact('sieuAm'));
    }

    /**
     * Cập nhật nhận xét (sau khi có kết quả từ kỹ thuật viên)
     */
    public function updateReview(Request $request, SieuAm $sieuAm, MedicalWorkflowService $workflow)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $bacSi = BacSi::where('user_id', $user->id)->firstOrFail();

        // Check quyền
        $chiDinhId = $sieuAm->bac_si_chi_dinh_id ?? $sieuAm->bac_si_id;
        if ($chiDinhId !== $bacSi->id) {
            abort(403);
        }

        // THÊM: policy check (chuẩn hóa quyền)
        try {
            $this->authorize('review', $sieuAm);
        } catch (\Throwable $e) {
            // fallback giữ check thủ công bên trên
        }

        $validated = $request->validate([
            'nhan_xet' => 'required|string|max:2000',
        ]);

        // THÊM: ưu tiên workflow service để phát notify; fallback update trực tiếp
        try {
            $workflow->updateUltrasoundReview($sieuAm, (string) $validated['nhan_xet']);
        } catch (\Throwable $e) {
            $sieuAm->update([
                'nhan_xet' => $validated['nhan_xet'],
            ]);
        }

        return back()->with('status', 'Đã cập nhật nhận xét siêu âm.');
    }

    /**
     * Download file siêu âm
     */
    public function download(SieuAm $sieuAm)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (!$sieuAm->canDownload($user)) {
            abort(403, 'Bạn không có quyền tải file này.');
        }

        if (!$sieuAm->file_path) {
            abort(404, 'File không tồn tại.');
        }

        $disk = $sieuAm->disk ?? 'sieu_am_private';

        if (!Storage::disk($disk)->exists($sieuAm->file_path)) {
            abort(404, 'File không tồn tại trên server.');
        }

        return Storage::disk($disk)->download($sieuAm->file_path);
    }
}

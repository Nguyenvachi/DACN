<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\BenhAn;
use App\Models\LoaiXQuang;
use App\Models\XQuang;
use App\Services\MedicalWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Controller quản lý X-Quang của Bác sĩ
 * Workflow: Bác sĩ chỉ định → Kỹ thuật viên thực hiện → Upload kết quả
 */
class XQuangController extends Controller
{
    protected $workflowService;

    public function __construct(MedicalWorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    /**
     * Hiển thị form chỉ định X-Quang
     */
    public function create(BenhAn $benhAn)
    {
        $bacSi = auth()->user()->bacSi;

        if (!$bacSi || $benhAn->bac_si_id !== $bacSi->id) {
            abort(403, 'Bạn không có quyền chỉ định X-Quang cho bệnh án này');
        }

        $lichHen = $benhAn->lichHen;
        if (!$lichHen || !in_array($lichHen->trang_thai, ['Đang khám', 'Hoàn thành'])) {
            return redirect()->back()->with('error', 'Chỉ chỉ định X-Quang khi đang khám hoặc đã hoàn thành');
        }

        $loaiXQuangs = LoaiXQuang::query()
            ->where('active', true)
            ->orderBy('ten')
            ->get();

        $existing = XQuang::where('benh_an_id', $benhAn->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('doctor.xquang.create', compact('benhAn', 'loaiXQuangs', 'existing'));
    }

    /**
     * Lưu yêu cầu X-Quang
     */
    public function store(Request $request, BenhAn $benhAn)
    {
        $bacSi = auth()->user()->bacSi;

        if (!$bacSi || $benhAn->bac_si_id !== $bacSi->id) {
            abort(403);
        }

        $request->validate([
            'loai_x_quang_id' => 'nullable|integer|exists:loai_x_quangs,id',
            'loai' => 'nullable|string|max:255',
            'mo_ta' => 'nullable|string|max:1000',
        ], [
            'loai_x_quang_id.exists' => 'Loại X-Quang không hợp lệ',
        ]);

        if (!$request->filled('loai_x_quang_id') && !$request->filled('loai')) {
            return redirect()->back()->withInput()->with('error', 'Vui lòng chọn loại X-Quang');
        }

        try {
            $loai = null;
            $gia = 0;
            $loaiMaster = null;

            if ($request->filled('loai_x_quang_id')) {
                $loaiMaster = LoaiXQuang::findOrFail($request->loai_x_quang_id);
                $loai = $loaiMaster->ten;
                $gia = (float) ($loaiMaster->gia ?? 0);
            } else {
                $loai = (string) $request->loai;
            }

            $xQuang = $this->workflowService->createXQuangRequest($benhAn, [
                'loai_x_quang_id' => $loaiMaster?->id,
                'loai' => $loai,
                'gia' => $gia,
                'mo_ta' => $request->mo_ta,
                'ngay_chi_dinh' => now(),
            ]);

            Log::info("Bác sĩ #{$bacSi->id} đã chỉ định X-Quang #{$xQuang->id} cho bệnh án #{$benhAn->id}");

            return redirect()
                ->route('doctor.benhan.edit', $benhAn->id)
                ->with('success', "Đã chỉ định X-Quang \"{$loai}\". Kỹ thuật viên sẽ thực hiện và upload kết quả.");

        } catch (\Exception $e) {
            Log::error("Lỗi khi chỉ định X-Quang: " . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Xem chi tiết X-Quang
     */
    public function show(XQuang $xQuang)
    {
        $bacSi = auth()->user()->bacSi;

        if ($xQuang->bac_si_id !== $bacSi->id) {
            abort(403, 'Không có quyền xem X-Quang này');
        }

        $xQuang->load(['benhAn.user', 'benhAn.lichHen']);

        return view('doctor.xquang.show', compact('xQuang'));
    }

    /**
     * Upload kết quả X-Quang (cho Kỹ thuật viên hoặc Bác sĩ)
     */
    public function uploadResult(Request $request, XQuang $xQuang)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'nhan_xet' => 'nullable|string|max:1000',
            'ket_qua' => 'nullable|string|max:2000',
        ], [
            'file.required' => 'Vui lòng chọn file kết quả',
            'file.mimes' => 'File phải là PDF hoặc ảnh (JPG, PNG)',
            'file.max' => 'Dung lượng file không được vượt quá 10MB',
        ]);

        try {
            $this->workflowService->uploadXQuangResult(
                $xQuang,
                $request->file('file'),
                $request->nhan_xet,
                $request->ket_qua
            );

            Log::info("Đã upload kết quả X-Quang #{$xQuang->id}");

            return redirect()
                ->back()
                ->with('success', 'Đã upload kết quả X-Quang thành công');

        } catch (\Exception $e) {
            Log::error("Lỗi khi upload kết quả X-Quang: " . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Có lỗi khi upload file: ' . $e->getMessage());
        }
    }

    /**
     * Thêm nhận xét của bác sĩ vào kết quả X-Quang
     */
    public function addComment(Request $request, XQuang $xQuang)
    {
        $bacSi = auth()->user()->bacSi;

        if ($xQuang->bac_si_id !== $bacSi->id) {
            abort(403);
        }

        $request->validate([
            'nhan_xet_bac_si' => 'required|string|max:2000',
        ], [
            'nhan_xet_bac_si.required' => 'Vui lòng nhập nhận xét',
        ]);

        try {
            $this->workflowService->updateXQuangDoctorComment($xQuang, $request->nhan_xet_bac_si);

            return redirect()
                ->back()
                ->with('success', 'Đã thêm nhận xét thành công');

        } catch (\Exception $e) {
            Log::error("Lỗi khi thêm nhận xét X-Quang: " . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Download kết quả X-Quang
     */
    public function download(XQuang $xQuang)
    {
        $bacSi = auth()->user()->bacSi;

        if ($xQuang->bac_si_id !== $bacSi->id) {
            abort(403);
        }

        if (!$xQuang->file_path) {
            abort(404, 'Chưa có file kết quả');
        }

        $disk = $xQuang->disk ?? 'benh_an_private';

        if (!Storage::disk($disk)->exists($xQuang->file_path)) {
            return redirect()->back()->with('error', 'File không tồn tại');
        }

        return Storage::disk($disk)->download($xQuang->file_path);
    }

    /**
     * Xóa yêu cầu X-Quang (chỉ khi chưa có kết quả)
     */
    public function destroy(XQuang $xQuang)
    {
        $bacSi = auth()->user()->bacSi;

        if ($xQuang->bac_si_id !== $bacSi->id) {
            abort(403);
        }

        if ($xQuang->trang_thai === XQuang::STATUS_COMPLETED) {
            return redirect()->back()->with('error', 'Không thể xóa X-Quang đã có kết quả');
        }

        if ($xQuang->file_path) {
            Storage::disk($xQuang->disk ?? 'benh_an_private')->delete($xQuang->file_path);
        }

        $xQuang->delete();

        Log::info("Đã xóa X-Quang #{$xQuang->id}");

        return redirect()
            ->route('doctor.benhan.edit', $xQuang->benh_an_id)
            ->with('success', 'Đã xóa yêu cầu X-Quang');
    }

    /**
     * Danh sách X-Quang của bác sĩ
     */
    public function index(Request $request)
    {
        $bacSi = auth()->user()->bacSi;

        $query = XQuang::byBacSi($bacSi->id)
            ->with(['benhAn.user', 'benhAn.lichHen']);

        if ($request->filled('status')) {
            $query->where('trang_thai', $request->status);
        }

        if ($request->filled('loai')) {
            $query->where('loai', 'LIKE', '%' . $request->loai . '%');
        }

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        if (!empty($fromDate)) {
            $query->whereDate('ngay_chi_dinh', '>=', $fromDate);
        }
        if (!empty($toDate)) {
            $query->whereDate('ngay_chi_dinh', '<=', $toDate);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('benhAn.user', function ($q) use ($search) {
                $q->where('ho_ten', 'LIKE', '%' . $search . '%')
                    ->orWhere('so_dien_thoai', 'LIKE', '%' . $search . '%');
            });
        }

        $xQuangs = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total' => XQuang::byBacSi($bacSi->id)->count(),
            'pending' => XQuang::byBacSi($bacSi->id)->pending()->count(),
            'processing' => XQuang::byBacSi($bacSi->id)->processing()->count(),
            'completed' => XQuang::byBacSi($bacSi->id)->completed()->count(),
        ];

        $loaiXQuangs = XQuang::byBacSi($bacSi->id)
            ->distinct()
            ->pluck('loai')
            ->filter()
            ->sort();

        return view('doctor.xquang.index', compact('xQuangs', 'stats', 'loaiXQuangs'));
    }
}

<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\XetNghiem;
use App\Models\BenhAn;
use App\Models\LoaiXetNghiem;
use App\Services\MedicalWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Controller quản lý Xét nghiệm của Bác sĩ
 * Workflow: Bác sĩ chỉ định → Kỹ thuật viên thực hiện → Upload kết quả
 */
class XetNghiemController extends Controller
{
    protected $workflowService;

    public function __construct(MedicalWorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    /**
     * Hiển thị form chỉ định xét nghiệm
     */
    public function create(BenhAn $benhAn)
    {
        $bacSi = auth()->user()->bacSi;

        if (!$bacSi || $benhAn->bac_si_id !== $bacSi->id) {
            abort(403, 'Bạn không có quyền chỉ định xét nghiệm cho bệnh án này');
        }

        // Kiểm tra trạng thái lịch hẹn
        $lichHen = $benhAn->lichHen;
        if (!$lichHen || !in_array($lichHen->trang_thai, ['Đang khám', 'Hoàn thành'])) {
            return redirect()->back()->with('error', 'Chỉ chỉ định xét nghiệm khi đang khám hoặc đã hoàn thành');
        }

        // Load danh mục loại xét nghiệm từ DB (Admin quản lý)
        $loaiXetNghiems = LoaiXetNghiem::query()
            ->where('active', true)
            ->orderBy('ten')
            ->get();

        // Lấy danh sách xét nghiệm đã chỉ định
        $existingTests = XetNghiem::where('benh_an_id', $benhAn->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('doctor.xetnghiem.create', compact('benhAn', 'loaiXetNghiems', 'existingTests'));
    }

    /**
     * Lưu yêu cầu xét nghiệm
     */
    public function store(Request $request, BenhAn $benhAn)
    {
        $bacSi = auth()->user()->bacSi;

        if (!$bacSi || $benhAn->bac_si_id !== $bacSi->id) {
            abort(403);
        }

        // Hỗ trợ cả 2 kiểu:
        // - mới: loai_xet_nghiem_id (chọn từ danh mục)
        // - cũ: loai (string) để không phá luồng/đã tồn tại
        $request->validate([
            'loai_xet_nghiem_id' => 'nullable|integer|exists:loai_xet_nghiems,id',
            'loai' => 'nullable|string|max:255',
            'mo_ta' => 'nullable|string|max:1000',
        ], [
            'loai_xet_nghiem_id.exists' => 'Loại xét nghiệm không hợp lệ',
        ]);

        if (!$request->filled('loai_xet_nghiem_id') && !$request->filled('loai')) {
            return redirect()->back()->withInput()->with('error', 'Vui lòng chọn loại xét nghiệm');
        }

        try {
            $loaiXN = null;
            if ($request->filled('loai_xet_nghiem_id')) {
                $loaiXN = LoaiXetNghiem::findOrFail($request->loai_xet_nghiem_id);
            }

            $loaiText = $loaiXN ? $loaiXN->ten : (string) $request->loai;
            $gia = $loaiXN ? (float) $loaiXN->gia : 0;

            $xetNghiem = $this->workflowService->createTestRequest($benhAn, [
                'loai_xet_nghiem_id' => $loaiXN?->id,
                'loai' => $loaiText,
                'gia' => $gia,
                'mo_ta' => $request->mo_ta,
            ]);

            Log::info("Bác sĩ #{$bacSi->id} đã chỉ định xét nghiệm #{$xetNghiem->id} cho bệnh án #{$benhAn->id}");

            return redirect()
                ->route('doctor.benhan.edit', $benhAn->id)
                ->with('success', "Đã chỉ định xét nghiệm \"{$loaiText}\". Kỹ thuật viên sẽ thực hiện và upload kết quả.");

        } catch (\Exception $e) {
            Log::error("Lỗi khi chỉ định xét nghiệm: " . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Xem kết quả xét nghiệm
     */
    public function show(XetNghiem $xetNghiem)
    {
        $bacSi = auth()->user()->bacSi;

        // Bác sĩ chỉ xem được xét nghiệm của mình chỉ định
        if ($xetNghiem->bac_si_id !== $bacSi->id) {
            abort(403, 'Không có quyền xem xét nghiệm này');
        }

        $xetNghiem->load(['benhAn.user', 'benhAn.lichHen']);

        return view('doctor.xetnghiem.show', compact('xetNghiem'));
    }

    /**
     * Upload kết quả xét nghiệm (cho Kỹ thuật viên hoặc Bác sĩ)
     * Note: Có thể dùng cho cả Doctor và Staff
     */
    public function uploadResult(Request $request, XetNghiem $xetNghiem)
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
            // Sử dụng service để upload kết quả
            $this->workflowService->uploadTestResult(
                $xetNghiem,
                $request->file('file'),
                $request->nhan_xet,
                $request->ket_qua
            );

            Log::info("Đã upload kết quả xét nghiệm #{$xetNghiem->id}");

            return redirect()
                ->back()
                ->with('success', 'Đã upload kết quả xét nghiệm thành công');

        } catch (\Exception $e) {
            Log::error("Lỗi khi upload kết quả xét nghiệm: " . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Có lỗi khi upload file: ' . $e->getMessage());
        }
    }

    /**
     * Download kết quả xét nghiệm
     */
    public function download(XetNghiem $xetNghiem)
    {
        $bacSi = auth()->user()->bacSi;

        // Bác sĩ chỉ download được xét nghiệm của mình
        if ($xetNghiem->bac_si_id !== $bacSi->id) {
            abort(403);
        }

        if (!$xetNghiem->file_path) {
            abort(404, 'Chưa có file kết quả');
        }

        $disk = $xetNghiem->disk ?? 'benh_an_private';

        if (!Storage::disk($disk)->exists($xetNghiem->file_path)) {
            return redirect()->back()->with('error', 'File không tồn tại');
        }

        return Storage::disk($disk)->download($xetNghiem->file_path);
    }

    /**
     * Xóa xét nghiệm (chỉ khi chưa có kết quả)
     */
    public function destroy(XetNghiem $xetNghiem)
    {
        $bacSi = auth()->user()->bacSi;

        if ($xetNghiem->bac_si_id !== $bacSi->id) {
            abort(403);
        }

        // Không cho xóa nếu đã có kết quả
        if ($xetNghiem->trang_thai === XetNghiem::STATUS_COMPLETED) {
            return redirect()->back()->with('error', 'Không thể xóa xét nghiệm đã có kết quả');
        }

        // Xóa file nếu có
        if ($xetNghiem->file_path) {
            Storage::disk($xetNghiem->disk ?? 'benh_an_private')->delete($xetNghiem->file_path);
        }

        $xetNghiem->delete();

        Log::info("Đã xóa xét nghiệm #{$xetNghiem->id}");

        return redirect()
            ->route('doctor.benhan.edit', $xetNghiem->benh_an_id)
            ->with('success', 'Đã xóa yêu cầu xét nghiệm');
    }

    /**
     * Danh sách xét nghiệm của bác sĩ
     * Parent file: app/Http/Controllers/Doctor/XetNghiemController.php
     */
    public function index(Request $request)
    {
        $bacSi = auth()->user()->bacSi;

        // Query cơ bản
        $query = XetNghiem::byBacSi($bacSi->id)
            ->with(['benhAn.user', 'benhAn.lichHen']);

        // Filter theo trạng thái
        if ($request->filled('status')) {
            $query->where('trang_thai', $request->status);
        }

        // Filter theo loại xét nghiệm
        if ($request->filled('loai')) {
            $query->where('loai', 'LIKE', '%' . $request->loai . '%');
        }

        // Filter theo ngày
        if ($request->filled('from_date')) {
            $query->whereDate('ngay_chi_dinh', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('ngay_chi_dinh', '<=', $request->to_date);
        }

        // Tìm kiếm theo tên bệnh nhân
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('benhAn.user', function($q) use ($search) {
                $q->where('ho_ten', 'LIKE', '%' . $search . '%')
                  ->orWhere('so_dien_thoai', 'LIKE', '%' . $search . '%');
            });
        }

        $xetNghiems = $query->orderBy('created_at', 'desc')->paginate(20);

        // Thống kê
        $stats = [
            'total' => XetNghiem::byBacSi($bacSi->id)->count(),
            'pending' => XetNghiem::byBacSi($bacSi->id)->pending()->count(),
            'processing' => XetNghiem::byBacSi($bacSi->id)->where('trang_thai', 'processing')->count(),
            'completed' => XetNghiem::byBacSi($bacSi->id)->completed()->count(),
        ];

        // Các loại xét nghiệm (cho filter)
        $loaiXetNghiems = XetNghiem::byBacSi($bacSi->id)
            ->distinct()
            ->pluck('loai')
            ->filter()
            ->sort();

        return view('doctor.xetnghiem.index', compact('xetNghiems', 'stats', 'loaiXetNghiems'));
    }

    /**
     * Thêm nhận xét của bác sĩ vào kết quả xét nghiệm
     * Parent file: app/Http/Controllers/Doctor/XetNghiemController.php
     */
    public function addComment(Request $request, XetNghiem $xetNghiem)
    {
        $bacSi = auth()->user()->bacSi;

        if ($xetNghiem->bac_si_id !== $bacSi->id) {
            abort(403);
        }

        $request->validate([
            'nhan_xet_bac_si' => 'required|string|max:2000',
        ], [
            'nhan_xet_bac_si.required' => 'Vui lòng nhập nhận xét',
        ]);

        try {
            $this->workflowService->updateDoctorComment($xetNghiem, $request->nhan_xet_bac_si);

            return redirect()
                ->back()
                ->with('success', 'Đã thêm nhận xét thành công');

        } catch (\Exception $e) {
            Log::error("Lỗi khi thêm nhận xét: " . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}

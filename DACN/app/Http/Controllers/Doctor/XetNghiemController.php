<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\XetNghiem;
use App\Models\BenhAn;
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
        if (!$lichHen || !in_array($lichHen->trang_thai, [\App\Models\LichHen::STATUS_IN_PROGRESS_VN, \App\Models\LichHen::STATUS_COMPLETED_VN])) {
            return redirect()->back()->with('error', 'Chỉ chỉ định xét nghiệm khi đang khám hoặc đã hoàn thành');
        }

        // Các loại xét nghiệm thường gặp
        $loaiXetNghiem = [
            'Xét nghiệm máu',
            'Xét nghiệm nước tiểu',
            'X-quang',
            'Siêu âm',
            'CT Scan',
            'MRI',
            'Điện tim',
            'Nội soi',
            'Sinh thiết',
            'Khác',
        ];

        // Lấy danh sách xét nghiệm đã chỉ định
        $existingTests = XetNghiem::where('benh_an_id', $benhAn->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('doctor.xetnghiem.create', compact('benhAn', 'loaiXetNghiem', 'existingTests'));
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

        $request->validate([
            'loai' => 'required|string|max:255',
            'mo_ta' => 'nullable|string|max:1000',
        ], [
            'loai.required' => 'Vui lòng chọn loại xét nghiệm',
        ]);

        try {
            $xetNghiem = $this->workflowService->createTestRequest($benhAn, [
                'loai' => $request->loai,
                'mo_ta' => $request->mo_ta,
            ]);

            Log::info("Bác sĩ #{$bacSi->id} đã chỉ định xét nghiệm #{$xetNghiem->id} cho bệnh án #{$benhAn->id}");

            return redirect()
                ->route('doctor.benhan.edit', $benhAn->id)
                ->with('success', "Đã chỉ định xét nghiệm \"{$request->loai}\". Kỹ thuật viên sẽ thực hiện và upload kết quả.");

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
     * Upload kết quả xét nghiệm (cho Kỹ thuật viên)
     * Note: Có thể chuyển sang StaffController nếu cần
     */
    public function uploadResult(Request $request, XetNghiem $xetNghiem)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // Max 10MB
        ], [
            'file.required' => 'Vui lòng chọn file kết quả',
            'file.mimes' => 'File phải là PDF hoặc ảnh (JPG, PNG)',
            'file.max' => 'Dung lượng file không được vượt quá 10MB',
        ]);

        try {
            // Xóa file cũ nếu có
            if ($xetNghiem->file_path) {
                Storage::disk($xetNghiem->disk ?? 'private')->delete($xetNghiem->file_path);
            }

            // Upload file mới
            $file = $request->file('file');
            $filename = 'xetnghiem_' . $xetNghiem->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('xet-nghiem', $filename, 'private');

            // Cập nhật xét nghiệm
            $this->workflowService->updateTestResult($xetNghiem, $path, 'private');

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
            return redirect()->back()->with('error', 'Chưa có kết quả xét nghiệm');
        }

        $disk = $xetNghiem->disk ?? 'private';

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
            Storage::disk($xetNghiem->disk ?? 'private')->delete($xetNghiem->file_path);
        }

        $xetNghiem->delete();

        Log::info("Đã xóa xét nghiệm #{$xetNghiem->id}");

        return redirect()
            ->route('doctor.benhan.edit', $xetNghiem->benh_an_id)
            ->with('success', 'Đã xóa yêu cầu xét nghiệm');
    }

    /**
     * Danh sách xét nghiệm của bác sĩ
     */
    public function index()
    {
        $bacSi = auth()->user()->bacSi;

        $xetNghiems = XetNghiem::where('bac_si_id', $bacSi->id)
            ->with(['benhAn.user', 'benhAn.lichHen'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('doctor.xetnghiem.index', compact('xetNghiems'));
    }
}

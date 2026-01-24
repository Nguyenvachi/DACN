<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\BacSi;
use App\Models\XetNghiem;
use App\Services\MedicalWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Controller xử lý Xét nghiệm - Staff (Kỹ thuật viên)
 * Chức năng: Xem XN chờ xử lý, Upload kết quả
 */
class XetNghiemController extends Controller
{
    protected $workflowService;

    public function __construct(MedicalWorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    /**
     * Danh sách xét nghiệm chờ thực hiện
     */
    public function pending()
    {
        $xetNghiems = XetNghiem::whereIn('trang_thai', ['pending', 'processing'])
            ->with(['benhAn.user', 'bacSi.user'])
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        // Thống kê
        $stats = [
            'pending' => XetNghiem::pending()->count(),
            'processing' => XetNghiem::processing()->count(),
            'completed_today' => XetNghiem::completed()
                ->whereDate('updated_at', today())
                ->count(),
        ];

        return view('staff.xetnghiem.pending', compact('xetNghiems', 'stats'));
    }

    /**
     * Form upload kết quả
     */
    public function uploadForm(XetNghiem $xetNghiem)
    {
        // Kiểm tra xem đã hoàn thành chưa
        if ($xetNghiem->trang_thai === XetNghiem::STATUS_COMPLETED) {
            return redirect()
                ->route('staff.xetnghiem.pending')
                ->with('warning', 'Xét nghiệm này đã có kết quả');
        }

        $xetNghiem->load(['benhAn.user', 'bacSi.user']);

        return view('staff.xetnghiem.upload', compact('xetNghiem'));
    }

    /**
     * Xử lý upload kết quả
     */
    public function upload(Request $request, XetNghiem $xetNghiem)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'ket_qua' => 'nullable|string|max:2000',
            'nhan_xet' => 'nullable|string|max:1000',
        ], [
            'file.required' => 'Vui lòng chọn file kết quả',
            'file.mimes' => 'File phải là PDF hoặc ảnh (JPG, PNG)',
            'file.max' => 'Dung lượng file không được vượt quá 10MB',
        ]);

        try {
            // Sử dụng service để upload
            $this->workflowService->uploadTestResult(
                $xetNghiem,
                $request->file('file'),
                $request->nhan_xet,
                $request->ket_qua
            );

            Log::info("Staff #{auth()->id()} upload kết quả xét nghiệm #{$xetNghiem->id}");

            return redirect()
                ->route('staff.xetnghiem.pending')
                ->with('success', "Đã upload kết quả xét nghiệm #{$xetNghiem->id} thành công");

        } catch (\Exception $e) {
            Log::error("Lỗi upload kết quả XN: " . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra khi upload file: ' . $e->getMessage());
        }
    }

    /**
     * Danh sách xét nghiệm đã hoàn thành
     */
    public function completed(Request $request)
    {
        $query = XetNghiem::completed()
            ->with(['benhAn.user', 'bacSi.user']);

        // Filter theo tìm kiếm (UI mới dùng search)
        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', '%' . $search . '%')
                    ->orWhere('loai', 'like', '%' . $search . '%')
                    ->orWhereHas('benhAn.user', function ($u) use ($search) {
                        $u->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%')
                            ->orWhere('so_dien_thoai', 'like', '%' . $search . '%');
                    });
            });
        }

        // Filter theo bác sĩ chỉ định (UI mới dùng bac_si_id)
        if ($request->filled('bac_si_id')) {
            $query->where('bac_si_id', $request->bac_si_id);
        }

        // Filter theo ngày
        $fromDate = $request->input('from_date', $request->input('tu_ngay'));
        $toDate = $request->input('to_date', $request->input('den_ngay'));
        if (!empty($fromDate)) {
            $query->whereDate('updated_at', '>=', $fromDate);
        }
        if (!empty($toDate)) {
            $query->whereDate('updated_at', '<=', $toDate);
        }

        $xetNghiems = $query->orderBy('updated_at', 'desc')->paginate(20);

        $todayCount = XetNghiem::completed()
            ->whereDate('updated_at', today())
            ->count();

        $weekCount = XetNghiem::completed()
            ->whereBetween('updated_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        // Thống kê
        $stats = [
            'today' => $todayCount,
            'this_week' => $weekCount,
            'this_month' => XetNghiem::completed()
                ->whereMonth('updated_at', now()->month)
                ->count(),
        ];

        $bacSis = BacSi::with('user')->orderBy('ho_ten')->get();

        return view('staff.xetnghiem.completed', compact('xetNghiems', 'stats', 'todayCount', 'weekCount', 'bacSis'));
    }

    /**
     * Xem chi tiết xét nghiệm
     */
    public function show(XetNghiem $xetNghiem)
    {
        $xetNghiem->load(['benhAn.user', 'bacSi.user', 'benhAn.lichHen']);

        return view('staff.xetnghiem.show', compact('xetNghiem'));
    }

    /**
     * Cập nhật trạng thái sang "processing"
     */
    public function markAsProcessing(XetNghiem $xetNghiem)
    {
        if ($xetNghiem->trang_thai === XetNghiem::STATUS_PENDING) {
            $xetNghiem->update(['trang_thai' => XetNghiem::STATUS_PROCESSING]);

            Log::info("Staff #{auth()->id()} bắt đầu xử lý XN #{$xetNghiem->id}");

            return redirect()
                ->back()
                ->with('success', 'Đã chuyển trạng thái sang "Đang xử lý"');
        }

        return redirect()->back();
    }
}

<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\BacSi;
use App\Models\XQuang;
use App\Services\MedicalWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Controller xử lý X-Quang - Staff (Kỹ thuật viên)
 * Chức năng: Xem X-Quang chờ xử lý, Upload kết quả
 */
class XQuangController extends Controller
{
    protected $workflowService;

    public function __construct(MedicalWorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    public function pending()
    {
        $xQuangs = XQuang::whereIn('trang_thai', ['pending', 'processing'])
            ->with(['benhAn.user', 'bacSi.user'])
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        $stats = [
            'pending' => XQuang::pending()->count(),
            'processing' => XQuang::processing()->count(),
            'completed_today' => XQuang::completed()->whereDate('updated_at', today())->count(),
        ];

        return view('staff.xquang.pending', compact('xQuangs', 'stats'));
    }

    public function uploadForm(XQuang $xQuang)
    {
        if ($xQuang->trang_thai === XQuang::STATUS_COMPLETED) {
            return redirect()
                ->route('staff.xquang.pending')
                ->with('warning', 'X-Quang này đã có kết quả');
        }

        $xQuang->load(['benhAn.user', 'bacSi.user']);

        return view('staff.xquang.upload', compact('xQuang'));
    }

    public function upload(Request $request, XQuang $xQuang)
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
            $this->workflowService->uploadXQuangResult(
                $xQuang,
                $request->file('file'),
                $request->nhan_xet,
                $request->ket_qua
            );

            Log::info("Staff #{auth()->id()} upload kết quả X-Quang #{$xQuang->id}");

            return redirect()
                ->route('staff.xquang.pending')
                ->with('success', "Đã upload kết quả X-Quang #{$xQuang->id} thành công");

        } catch (\Exception $e) {
            Log::error("Lỗi upload kết quả X-Quang: " . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra khi upload file: ' . $e->getMessage());
        }
    }

    public function completed(Request $request)
    {
        $query = XQuang::completed()->with(['benhAn.user', 'bacSi.user']);

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

        if ($request->filled('bac_si_id')) {
            $query->where('bac_si_id', $request->bac_si_id);
        }

        $fromDate = $request->input('from_date', $request->input('tu_ngay'));
        $toDate = $request->input('to_date', $request->input('den_ngay'));
        if (!empty($fromDate)) {
            $query->whereDate('updated_at', '>=', $fromDate);
        }
        if (!empty($toDate)) {
            $query->whereDate('updated_at', '<=', $toDate);
        }

        $xQuangs = $query->orderBy('updated_at', 'desc')->paginate(20);

        $todayCount = XQuang::completed()->whereDate('updated_at', today())->count();
        $weekCount = XQuang::completed()->whereBetween('updated_at', [now()->startOfWeek(), now()->endOfWeek()])->count();

        $stats = [
            'today' => $todayCount,
            'this_week' => $weekCount,
            'this_month' => XQuang::completed()->whereMonth('updated_at', now()->month)->count(),
        ];

        $bacSis = BacSi::with('user')->orderBy('ho_ten')->get();

        return view('staff.xquang.completed', compact('xQuangs', 'stats', 'todayCount', 'weekCount', 'bacSis'));
    }

    public function show(XQuang $xQuang)
    {
        $xQuang->load(['benhAn.user', 'bacSi.user', 'benhAn.lichHen']);

        return view('staff.xquang.show', compact('xQuang'));
    }

    public function markAsProcessing(XQuang $xQuang)
    {
        if ($xQuang->trang_thai === XQuang::STATUS_PENDING) {
            $xQuang->update(['trang_thai' => XQuang::STATUS_PROCESSING]);

            Log::info("Staff #{auth()->id()} bắt đầu xử lý X-Quang #{$xQuang->id}");

            return redirect()
                ->back()
                ->with('success', 'Đã chuyển trạng thái sang "Đang xử lý"');
        }

        return redirect()->back();
    }
}

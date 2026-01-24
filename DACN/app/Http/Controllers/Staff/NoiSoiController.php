<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\BacSi;
use App\Models\NoiSoi;
use App\Services\MedicalWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Controller xử lý Nội soi - Staff (Kỹ thuật viên)
 */
class NoiSoiController extends Controller
{
    protected $workflowService;

    public function __construct(MedicalWorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    public function pending()
    {
        $noiSois = NoiSoi::whereIn('trang_thai', ['pending', 'processing'])
            ->with(['benhAn.user', 'bacSiChiDinh.user', 'loaiNoiSoi'])
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        $stats = [
            'pending' => NoiSoi::pending()->count(),
            'processing' => NoiSoi::processing()->count(),
            'completed_today' => NoiSoi::completed()->whereDate('updated_at', today())->count(),
        ];

        return view('staff.noisoi.pending', compact('noiSois', 'stats'));
    }

    public function uploadForm(NoiSoi $noiSoi)
    {
        if ($noiSoi->trang_thai === NoiSoi::STATUS_COMPLETED) {
            return redirect()->route('staff.noisoi.pending')->with('warning', 'Nội soi này đã có kết quả');
        }

        $noiSoi->load(['benhAn.user', 'bacSiChiDinh.user']);
        $noiSoi->loadMissing('loaiNoiSoi');

        return view('staff.noisoi.upload', compact('noiSoi'));
    }

    public function upload(Request $request, NoiSoi $noiSoi)
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
            $this->workflowService->uploadNoiSoiResult(
                $noiSoi,
                $request->file('file'),
                $request->nhan_xet,
                $request->ket_qua
            );

            Log::info('Staff #' . auth()->id() . " upload kết quả Nội soi #{$noiSoi->id}");

            return redirect()->route('staff.noisoi.pending')->with('success', "Đã upload kết quả Nội soi #{$noiSoi->id} thành công");
        } catch (\Exception $e) {
            Log::error('Lỗi upload kết quả Nội soi: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Có lỗi xảy ra khi upload file: ' . $e->getMessage());
        }
    }

    public function completed(Request $request)
    {
        $query = NoiSoi::completed()->with(['benhAn.user', 'bacSiChiDinh.user', 'loaiNoiSoi']);

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
            $query->where('bac_si_chi_dinh_id', $request->bac_si_id);
        }

        $fromDate = $request->input('from_date', $request->input('tu_ngay'));
        $toDate = $request->input('to_date', $request->input('den_ngay'));
        if (!empty($fromDate)) {
            $query->whereDate('updated_at', '>=', $fromDate);
        }
        if (!empty($toDate)) {
            $query->whereDate('updated_at', '<=', $toDate);
        }

        $noiSois = $query->orderBy('updated_at', 'desc')->paginate(20);

        $todayCount = NoiSoi::completed()->whereDate('updated_at', today())->count();
        $weekCount = NoiSoi::completed()->whereBetween('updated_at', [now()->startOfWeek(), now()->endOfWeek()])->count();

        $stats = [
            'today' => $todayCount,
            'this_week' => $weekCount,
            'this_month' => NoiSoi::completed()->whereMonth('updated_at', now()->month)->count(),
        ];

        $bacSis = BacSi::with('user')->orderBy('ho_ten')->get();

        return view('staff.noisoi.completed', compact('noiSois', 'stats', 'todayCount', 'weekCount', 'bacSis'));
    }

    public function show(NoiSoi $noiSoi)
    {
        $noiSoi->load(['benhAn.user', 'bacSiChiDinh.user', 'benhAn.lichHen', 'loaiNoiSoi']);

        return view('staff.noisoi.show', compact('noiSoi'));
    }

    public function markAsProcessing(NoiSoi $noiSoi)
    {
        if ($noiSoi->trang_thai === NoiSoi::STATUS_PENDING) {
            $noiSoi->update([
                'trang_thai' => NoiSoi::STATUS_PROCESSING,
                'ngay_thuc_hien' => now(),
            ]);

            Log::info('Staff #' . auth()->id() . " bắt đầu xử lý Nội soi #{$noiSoi->id}");

            return redirect()->back()->with('success', 'Đã chuyển trạng thái sang "Đang xử lý"');
        }

        return redirect()->back();
    }
}

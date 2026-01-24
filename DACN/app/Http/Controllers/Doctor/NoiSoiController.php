<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\BenhAn;
use App\Models\LoaiNoiSoi;
use App\Models\NoiSoi;
use App\Services\MedicalWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Controller quản lý Nội soi của Bác sĩ
 * Workflow: Bác sĩ chỉ định → Staff xử lý → Upload kết quả
 */
class NoiSoiController extends Controller
{
    protected $workflowService;

    public function __construct(MedicalWorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    public function index(Request $request)
    {
        $bacSi = auth()->user()->bacSi;

        $query = NoiSoi::query()
            ->where('bac_si_chi_dinh_id', $bacSi?->id)
            ->with(['benhAn.user', 'benhAn.lichHen', 'loaiNoiSoi']);

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
                    ->orWhere('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('so_dien_thoai', 'LIKE', '%' . $search . '%');
            });
        }

        $noiSois = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total' => NoiSoi::where('bac_si_chi_dinh_id', $bacSi?->id)->count(),
            'pending' => NoiSoi::where('bac_si_chi_dinh_id', $bacSi?->id)->pending()->count(),
            'processing' => NoiSoi::where('bac_si_chi_dinh_id', $bacSi?->id)->processing()->count(),
            'completed' => NoiSoi::where('bac_si_chi_dinh_id', $bacSi?->id)->completed()->count(),
        ];

        $loaiNoiSois = NoiSoi::where('bac_si_chi_dinh_id', $bacSi?->id)
            ->distinct()
            ->pluck('loai')
            ->filter()
            ->sort();

        return view('doctor.noisoi.index', compact('noiSois', 'stats', 'loaiNoiSois'));
    }

    public function create(BenhAn $benhAn)
    {
        $bacSi = auth()->user()->bacSi;

        if (!$bacSi || (int) $benhAn->bac_si_id !== (int) $bacSi->id) {
            abort(403, 'Bạn không có quyền chỉ định Nội soi cho bệnh án này');
        }

        $lichHen = $benhAn->lichHen;
        if (!$lichHen || !in_array($lichHen->trang_thai, ['Đang khám', 'Hoàn thành'])) {
            return redirect()->back()->with('error', 'Chỉ chỉ định Nội soi khi đang khám hoặc đã hoàn thành');
        }

        $loaiNoiSois = LoaiNoiSoi::query()
            ->where('active', true)
            ->orderBy('ten')
            ->get();

        $existing = NoiSoi::where('benh_an_id', $benhAn->id)
            ->with('loaiNoiSoi')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('doctor.noisoi.create', compact('benhAn', 'existing', 'loaiNoiSois'));
    }

    public function store(Request $request, BenhAn $benhAn)
    {
        $bacSi = auth()->user()->bacSi;

        if (!$bacSi || (int) $benhAn->bac_si_id !== (int) $bacSi->id) {
            abort(403);
        }

        $request->validate([
            'loai_noi_soi_id' => 'nullable|integer|exists:loai_noi_sois,id',
            'loai' => 'nullable|string|max:255',
            'mo_ta' => 'nullable|string|max:1000',
        ], [
            'loai_noi_soi_id.exists' => 'Loại Nội soi không hợp lệ',
        ]);

        if (! $request->filled('loai_noi_soi_id') && ! $request->filled('loai')) {
            return redirect()->back()->withInput()->with('error', 'Vui lòng chọn loại Nội soi');
        }

        try {
            $loai = null;
            $gia = 0;
            $loaiMaster = null;
            $phongId = null;

            if ($request->filled('loai_noi_soi_id')) {
                $loaiMaster = LoaiNoiSoi::findOrFail($request->loai_noi_soi_id);
                $loai = $loaiMaster->ten;
                $gia = (float) ($loaiMaster->gia ?? 0);
                $phongId = $loaiMaster->phong_id;
            } else {
                $loai = (string) $request->loai;
            }

            $noiSoi = $this->workflowService->createNoiSoiRequest($benhAn, [
                'loai_noi_soi_id' => $loaiMaster?->id,
                'phong_id' => $phongId,
                'loai' => $loai,
                'mo_ta' => $request->mo_ta,
                'gia' => $gia,
                'ngay_chi_dinh' => now(),
            ]);

            Log::info("Bác sĩ #{$bacSi->id} đã chỉ định Nội soi #{$noiSoi->id} cho bệnh án #{$benhAn->id}");

            return redirect()
                ->route('doctor.benhan.edit', $benhAn->id)
                ->with('success', "Đã chỉ định Nội soi \"{$loai}\". Kỹ thuật viên sẽ thực hiện và upload kết quả.");
        } catch (\Exception $e) {
            Log::error('Lỗi khi chỉ định Nội soi: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function show(NoiSoi $noiSoi)
    {
        $this->authorize('view', $noiSoi);
        $noiSoi->load(['benhAn.user', 'benhAn.lichHen', 'loaiNoiSoi']);

        return view('doctor.noisoi.show', compact('noiSoi'));
    }

    public function uploadResult(Request $request, NoiSoi $noiSoi)
    {
        $this->authorize('view', $noiSoi);

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
            $this->workflowService->uploadNoiSoiResult(
                $noiSoi,
                $request->file('file'),
                $request->nhan_xet,
                $request->ket_qua
            );

            return redirect()->back()->with('success', 'Đã upload kết quả Nội soi thành công');
        } catch (\Exception $e) {
            Log::error('Lỗi khi upload kết quả Nội soi: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Có lỗi khi upload file: ' . $e->getMessage());
        }
    }

    public function download(NoiSoi $noiSoi)
    {
        $this->authorize('view', $noiSoi);

        if (!$noiSoi->file_path) {
            abort(404, 'Chưa có file kết quả');
        }

        $disk = $noiSoi->disk ?? 'benh_an_private';
        if (!Storage::disk($disk)->exists($noiSoi->file_path)) {
            return redirect()->back()->with('error', 'File không tồn tại');
        }

        return Storage::disk($disk)->download($noiSoi->file_path);
    }

    public function destroy(NoiSoi $noiSoi)
    {
        $this->authorize('view', $noiSoi);

        if ($noiSoi->trang_thai === NoiSoi::STATUS_COMPLETED) {
            return redirect()->back()->with('error', 'Không thể xóa Nội soi đã có kết quả');
        }

        if ($noiSoi->file_path) {
            Storage::disk($noiSoi->disk ?? 'benh_an_private')->delete($noiSoi->file_path);
        }

        $benhAnId = $noiSoi->benh_an_id;
        $noiSoi->delete();

        return redirect()->route('doctor.benhan.edit', $benhAnId)->with('success', 'Đã xóa yêu cầu Nội soi');
    }
}

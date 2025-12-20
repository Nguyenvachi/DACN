<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\BenhAn;
use App\Models\TheoDoiThaiKy;
use App\Notifications\PregnancyTrackingStatusUpdated;
use App\Observers\BenhAnObserver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TheoDoiThaiKyController extends Controller
{
    /**
     * Danh sách theo dõi thai kỳ thuộc bác sĩ
     */
    public function index(Request $request)
    {
        $bacSi = auth()->user()->bacSi;

        if (!$bacSi) {
            abort(403, 'Tài khoản bác sĩ chưa được gắn hồ sơ bác sĩ');
        }

        $query = TheoDoiThaiKy::with(['benhAn.user', 'benhAn.lichHen'])
            ->whereHas('benhAn', function ($q) use ($bacSi) {
                $q->where('bac_si_id', $bacSi->id);
            });

        if ($request->filled('status')) {
            $query->where('trang_thai', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $query->whereHas('benhAn.user', function ($u) use ($search) {
                $u->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('so_dien_thoai', 'like', '%' . $search . '%')
                    ->orWhere('ho_ten', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('from_date')) {
            $query->whereDate('ngay_theo_doi', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('ngay_theo_doi', '<=', $request->to_date);
        }

        $records = $query->orderByDesc('created_at')->paginate(20);

        $baseStatsQuery = TheoDoiThaiKy::whereHas('benhAn', function ($q) use ($bacSi) {
            $q->where('bac_si_id', $bacSi->id);
        });

        $stats = [
            'total' => (clone $baseStatsQuery)->count(),
            'submitted' => (clone $baseStatsQuery)->where('trang_thai', TheoDoiThaiKy::STATUS_SUBMITTED)->count(),
            'reviewed' => (clone $baseStatsQuery)->where('trang_thai', TheoDoiThaiKy::STATUS_REVIEWED)->count(),
            'recorded' => (clone $baseStatsQuery)->where('trang_thai', TheoDoiThaiKy::STATUS_RECORDED)->count(),
            'archived' => (clone $baseStatsQuery)->where('trang_thai', TheoDoiThaiKy::STATUS_ARCHIVED)->count(),
        ];

        return view('doctor.theodoithaiky.index', compact('records', 'stats'));
    }

    public function create(BenhAn $benhAn)
    {
        $bacSi = auth()->user()->bacSi;
        if (!$bacSi || (int) $benhAn->bac_si_id !== (int) $bacSi->id) {
            abort(403, 'Bạn không có quyền tạo theo dõi cho bệnh án này');
        }

        return view('doctor.theodoithaiky.create', compact('benhAn'));
    }

    public function store(Request $request, BenhAn $benhAn)
    {
        $bacSi = auth()->user()->bacSi;
        if (!$bacSi || (int) $benhAn->bac_si_id !== (int) $bacSi->id) {
            abort(403);
        }

        $data = $request->validate([
            'ngay_theo_doi' => 'nullable|date',
            'tuan_thai' => 'nullable|integer|min:0|max:45',
            'can_nang_kg' => 'nullable|numeric|min:1|max:300',
            'huyet_ap_tam_thu' => 'nullable|integer|min:50|max:250',
            'huyet_ap_tam_truong' => 'nullable|integer|min:30|max:150',
            'nhip_tim_thai' => 'nullable|integer|min:50|max:220',
            'duong_huyet' => 'nullable|numeric|min:0|max:50',
            'huyet_sac_to' => 'nullable|numeric|min:0|max:25',
            'trieu_chung' => 'nullable|string|max:5000',
            'ghi_chu' => 'nullable|string|max:5000',
            'nhan_xet' => 'nullable|string|max:5000',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'file.mimes' => 'File phải là PDF hoặc ảnh (JPG, PNG)',
            'file.max' => 'Dung lượng file không được vượt quá 5MB',
        ]);

        $disk = 'benh_an_private';
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('theo_doi_thai_ky', $disk);
        }

        $record = TheoDoiThaiKy::create([
            'benh_an_id' => $benhAn->id,
            'user_id' => $benhAn->user_id,
            'bac_si_id' => $bacSi->id,
            'ngay_theo_doi' => $data['ngay_theo_doi'] ?? now()->toDateString(),
            'tuan_thai' => $data['tuan_thai'] ?? null,
            'can_nang_kg' => $data['can_nang_kg'] ?? null,
            'huyet_ap_tam_thu' => $data['huyet_ap_tam_thu'] ?? null,
            'huyet_ap_tam_truong' => $data['huyet_ap_tam_truong'] ?? null,
            'nhip_tim_thai' => $data['nhip_tim_thai'] ?? null,
            'duong_huyet' => $data['duong_huyet'] ?? null,
            'huyet_sac_to' => $data['huyet_sac_to'] ?? null,
            'trieu_chung' => $data['trieu_chung'] ?? null,
            'ghi_chu' => $data['ghi_chu'] ?? null,
            'nhan_xet' => $data['nhan_xet'] ?? null,
            'file_path' => $filePath,
            'disk' => $disk,
            'trang_thai' => TheoDoiThaiKy::STATUS_RECORDED,
        ]);

        return redirect()->route('doctor.theodoithaiky.show', $record)->with('success', 'Đã lưu theo dõi thai kỳ');
    }

    public function show(TheoDoiThaiKy $theoDoiThaiKy)
    {
        $theoDoiThaiKy->loadMissing(['benhAn.user', 'benhAn.lichHen']);
        $this->authorize('view', $theoDoiThaiKy);

        return view('doctor.theodoithaiky.show', ['record' => $theoDoiThaiKy]);
    }

    public function update(Request $request, TheoDoiThaiKy $theoDoiThaiKy)
    {
        $this->authorize('update', $theoDoiThaiKy);

        $oldStatus = $theoDoiThaiKy->trang_thai;

        $data = $request->validate([
            'nhan_xet' => 'nullable|string|max:5000',
            'ghi_chu' => 'nullable|string|max:5000',
            'trang_thai' => 'nullable|string|in:submitted,reviewed,recorded,archived',
        ]);

        $theoDoiThaiKy->update([
            'nhan_xet' => $data['nhan_xet'] ?? $theoDoiThaiKy->nhan_xet,
            'ghi_chu' => $data['ghi_chu'] ?? $theoDoiThaiKy->ghi_chu,
            'trang_thai' => $data['trang_thai'] ?? $theoDoiThaiKy->trang_thai,
        ]);

        // THÊM: thông báo cho bệnh nhân khi đổi trạng thái
        try {
            $theoDoiThaiKy->loadMissing(['benhAn.user']);
            $patient = $theoDoiThaiKy->benhAn?->user;

            if ($patient && $oldStatus !== $theoDoiThaiKy->trang_thai) {
                $actionUrl = null;
                try {
                    $actionUrl = route('patient.theodoithaiky.show', $theoDoiThaiKy->id);
                } catch (\Throwable $e) {
                    $actionUrl = null;
                }

                $patient->notify(new PregnancyTrackingStatusUpdated(
                    $theoDoiThaiKy,
                    'Cập nhật theo dõi thai kỳ: ' . $theoDoiThaiKy->trang_thai_text,
                    'Bác sĩ đã cập nhật trạng thái bản ghi theo dõi thai kỳ của bạn.',
                    $actionUrl
                ));
            }
        } catch (\Throwable $e) {
            Log::warning('Pregnancy tracking status update notification skipped', [
                'theo_doi_thai_ky_id' => $theoDoiThaiKy->id,
                'error' => $e->getMessage(),
            ]);
        }

        return back()->with('success', 'Đã cập nhật theo dõi thai kỳ');
    }

    public function download(TheoDoiThaiKy $theoDoiThaiKy)
    {
        $this->authorize('view', $theoDoiThaiKy);

        if (!$theoDoiThaiKy->file_path) {
            abort(404, 'Chưa có file đính kèm');
        }

        $disk = $theoDoiThaiKy->disk ?? 'benh_an_private';

        if (!Storage::disk($disk)->exists($theoDoiThaiKy->file_path)) {
            abort(404, 'File không tồn tại');
        }

        // THÊM: audit log download
        try {
            $theoDoiThaiKy->loadMissing(['benhAn']);
            if ($theoDoiThaiKy->benhAn) {
                BenhAnObserver::logCustomAction(
                    $theoDoiThaiKy->benhAn,
                    'pregnancy_tracking_downloaded',
                    'Tải tệp đính kèm theo dõi thai kỳ #' . $theoDoiThaiKy->id
                );
            }
        } catch (\Throwable $e) {
            // ignore audit failures
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $fs */
        $fs = Storage::disk($disk);

        return $fs->download($theoDoiThaiKy->file_path);
    }
}

<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\BenhAn;
use App\Models\TheoDoiThaiKy;
use App\Models\User;
use App\Notifications\PregnancyTrackingSubmitted;
use App\Observers\BenhAnObserver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TheoDoiThaiKyController extends Controller
{
    /**
     * Danh sách theo dõi thai kỳ của bệnh nhân
     */
    public function index(Request $request)
    {
        $query = TheoDoiThaiKy::with(['benhAn.bacSi.user', 'benhAn.lichHen'])
            ->whereHas('benhAn', function ($q) {
                $q->where('user_id', auth()->id());
            });

        if ($request->filled('status')) {
            $query->where('trang_thai', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('ngay_theo_doi', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('ngay_theo_doi', '<=', $request->to_date);
        }

        $records = $query->orderByDesc('created_at')->paginate(10);

        $baseStatsQuery = TheoDoiThaiKy::whereHas('benhAn', function ($q) {
            $q->where('user_id', auth()->id());
        });

        $stats = [
            'total' => (clone $baseStatsQuery)->count(),
            'submitted' => (clone $baseStatsQuery)->where('trang_thai', TheoDoiThaiKy::STATUS_SUBMITTED)->count(),
            'reviewed' => (clone $baseStatsQuery)->where('trang_thai', TheoDoiThaiKy::STATUS_REVIEWED)->count(),
            'recorded' => (clone $baseStatsQuery)->where('trang_thai', TheoDoiThaiKy::STATUS_RECORDED)->count(),
            'archived' => (clone $baseStatsQuery)->where('trang_thai', TheoDoiThaiKy::STATUS_ARCHIVED)->count(),
        ];

        return view('patient.theodoithaiky.index', compact('records', 'stats'));
    }

    public function create(Request $request)
    {
        $benhAns = BenhAn::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->get();

        $selectedBenhAn = null;
        if ($request->filled('benh_an_id')) {
            $selectedBenhAn = $benhAns->firstWhere('id', (int) $request->benh_an_id);
            if (!$selectedBenhAn) {
                abort(403);
            }
        }

        return view('patient.theodoithaiky.create', compact('benhAns', 'selectedBenhAn'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'benh_an_id' => 'required|integer|exists:benh_ans,id',
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
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'benh_an_id.required' => 'Vui lòng chọn bệnh án',
            'file.mimes' => 'File phải là PDF hoặc ảnh (JPG, PNG)',
            'file.max' => 'Dung lượng file không được vượt quá 5MB',
        ]);

        $benhAn = BenhAn::findOrFail($data['benh_an_id']);
        if ((int) $benhAn->user_id !== (int) auth()->id()) {
            abort(403);
        }

        $disk = 'benh_an_private';
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('theo_doi_thai_ky', $disk);
        }

        $record = TheoDoiThaiKy::create([
            'benh_an_id' => $benhAn->id,
            'user_id' => auth()->id(),
            'bac_si_id' => $benhAn->bac_si_id,
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
            'file_path' => $filePath,
            'disk' => $disk,
            'trang_thai' => TheoDoiThaiKy::STATUS_SUBMITTED,
        ]);

        // THÊM: Thông báo cho bác sĩ + staff (theo pattern các module cận lâm sàng)
        try {
            $benhAn->loadMissing(['bacSi.user', 'user']);

            $doctorUser = $benhAn->bacSi?->user;
            if ($doctorUser) {
                $actionUrl = null;
                try {
                    $actionUrl = route('doctor.theodoithaiky.show', $record->id);
                } catch (\Throwable $e) {
                    $actionUrl = null;
                }

                $doctorUser->notify(new PregnancyTrackingSubmitted(
                    $record,
                    'Có bản ghi theo dõi thai kỳ mới',
                    'Bệnh nhân vừa gửi bản ghi theo dõi thai kỳ. Vui lòng vào xem và cập nhật trạng thái nếu cần.',
                    $actionUrl
                ));
            }

            $staffUsers = User::role('staff')->get();
            foreach ($staffUsers as $staff) {
                $actionUrl = null;
                try {
                    $actionUrl = route('staff.theodoithaiky.show', $record->id);
                } catch (\Throwable $e) {
                    $actionUrl = null;
                }

                $staff->notify(new PregnancyTrackingSubmitted(
                    $record,
                    'Có bản ghi theo dõi thai kỳ mới',
                    'Có bản ghi theo dõi thai kỳ mới được gửi. Vui lòng theo dõi và hỗ trợ xử lý.',
                    $actionUrl
                ));
            }
        } catch (\Throwable $e) {
            Log::warning('Pregnancy tracking submitted notification skipped', [
                'theo_doi_thai_ky_id' => $record->id,
                'benh_an_id' => $benhAn->id,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()->route('patient.theodoithaiky.show', $record)->with('success', 'Đã gửi bản ghi theo dõi thai kỳ');
    }

    public function show(TheoDoiThaiKy $theoDoiThaiKy)
    {
        $theoDoiThaiKy->loadMissing(['benhAn.bacSi.user', 'benhAn.lichHen']);
        $this->authorize('view', $theoDoiThaiKy);

        return view('patient.theodoithaiky.show', ['record' => $theoDoiThaiKy]);
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

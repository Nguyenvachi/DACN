<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\BenhAn;
use App\Models\LichHen;
use App\Models\TaiKham;
use App\Notifications\CustomNotification;
use App\Observers\BenhAnObserver;
use Illuminate\Http\Request;

class TaiKhamController extends Controller
{
    public function index(Request $request)
    {
        $query = TaiKham::with(['benhAn.bacSi.user', 'lichHen'])
            ->whereHas('benhAn', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('trang_thai', $request->status);
        }

        $records = $query->paginate(10);

        $baseStatsQuery = TaiKham::whereHas('benhAn', function ($q) {
            $q->where('user_id', auth()->id());
        });

        $stats = [
            'total' => (clone $baseStatsQuery)->count(),
            'pending' => (clone $baseStatsQuery)->where('trang_thai', TaiKham::STATUS_PENDING_VN)->count(),
            'confirmed' => (clone $baseStatsQuery)->where('trang_thai', TaiKham::STATUS_CONFIRMED_VN)->count(),
            'booked' => (clone $baseStatsQuery)->where('trang_thai', TaiKham::STATUS_BOOKED_VN)->count(),
            'completed' => (clone $baseStatsQuery)->where('trang_thai', TaiKham::STATUS_COMPLETED_VN)->count(),
            'cancelled' => (clone $baseStatsQuery)->where('trang_thai', TaiKham::STATUS_CANCELLED_VN)->count(),
        ];

        return view('patient.taikham.index', compact('records', 'stats'));
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

        return view('patient.taikham.create', compact('benhAns', 'selectedBenhAn'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'benh_an_id' => 'required|integer|exists:benh_ans,id',
            'ngay_tai_kham' => 'nullable|date',
            'thoi_gian_tai_kham' => 'nullable|date_format:H:i',
            'so_ngay_du_kien' => 'nullable|integer|min:1|max:365',
            'ly_do' => 'nullable|string|max:5000',
            'ghi_chu' => 'nullable|string|max:5000',
        ], [
            'benh_an_id.required' => 'Vui lòng chọn bệnh án',
        ]);

        $benhAn = BenhAn::findOrFail($data['benh_an_id']);
        if ((int) $benhAn->user_id !== (int) auth()->id()) {
            abort(403);
        }

        // Chống trùng yêu cầu đang mở cho cùng bệnh án
        $openStatuses = [TaiKham::STATUS_PENDING_VN, TaiKham::STATUS_CONFIRMED_VN, TaiKham::STATUS_BOOKED_VN];
        $existingOpen = TaiKham::where('benh_an_id', $benhAn->id)
            ->whereIn('trang_thai', $openStatuses)
            ->exists();
        if ($existingOpen) {
            return back()->with('error', 'Bệnh án này đã có yêu cầu tái khám đang xử lý. Vui lòng theo dõi yêu cầu hiện tại hoặc liên hệ nhân viên.');
        }

        $record = TaiKham::create([
            'benh_an_id' => $benhAn->id,
            'user_id' => auth()->id(),
            'bac_si_id' => $benhAn->bac_si_id,
            'ngay_tai_kham' => $data['ngay_tai_kham'] ?? null,
            'thoi_gian_tai_kham' => $data['thoi_gian_tai_kham'] ?? null,
            'so_ngay_du_kien' => $data['so_ngay_du_kien'] ?? null,
            'ly_do' => $data['ly_do'] ?? null,
            'ghi_chu' => $data['ghi_chu'] ?? null,
            'trang_thai' => TaiKham::STATUS_PENDING_VN,
            'created_by_role' => auth()->user()->roleKey(),
        ]);

        BenhAnObserver::logCustomActionWithValues(
            $benhAn,
            'tai_kham_created_by_patient',
            null,
            ['tai_kham' => $record->toArray()]
        );

        // Thông báo in-app cho bác sĩ
        $doctorUser = $record->bacSi?->user;
        if ($doctorUser) {
            $doctorUser->notify(new CustomNotification(
                'Có yêu cầu tái khám mới',
                'Bệnh nhân đã gửi yêu cầu tái khám (#' . $record->id . ').',
                route('doctor.taikham.show', $record)
            ));
        }

        return redirect()->route('patient.taikham.show', $record)->with('success', 'Đã gửi yêu cầu tái khám');
    }

    public function show(TaiKham $taiKham)
    {
        $taiKham->loadMissing(['benhAn.bacSi.user', 'benhAn.lichHen', 'lichHen']);
        $this->authorize('view', $taiKham);

        return view('patient.taikham.show', ['record' => $taiKham]);
    }

    public function confirm(TaiKham $taiKham)
    {
        $this->authorize('update', $taiKham);

        $taiKham->loadMissing(['benhAn', 'bacSi.user', 'user']);
        $before = $taiKham->toArray();

        $taiKham->update([
            'trang_thai' => TaiKham::STATUS_CONFIRMED_VN,
        ]);

        if ($taiKham->benhAn) {
            BenhAnObserver::logCustomActionWithValues(
                $taiKham->benhAn,
                'tai_kham_confirmed_by_patient',
                ['tai_kham' => $before],
                ['tai_kham' => $taiKham->toArray()]
            );
        }

        $doctorUser = $taiKham->bacSi?->user;
        if ($doctorUser) {
            $doctorUser->notify(new CustomNotification(
                'Bệnh nhân xác nhận tái khám',
                'Yêu cầu tái khám #' . $taiKham->id . ' đã được bệnh nhân xác nhận.',
                route('doctor.taikham.show', $taiKham)
            ));
        }

        return back()->with('success', 'Bạn đã xác nhận tái khám');
    }

    public function cancel(TaiKham $taiKham)
    {
        $this->authorize('update', $taiKham);

        $taiKham->loadMissing(['benhAn', 'bacSi.user', 'user', 'lichHen']);
        $before = $taiKham->toArray();

        $taiKham->update([
            'trang_thai' => TaiKham::STATUS_CANCELLED_VN,
        ]);

        // Sync về lịch hẹn nếu đã đặt
        if ($taiKham->lichHen && $taiKham->lichHen->trang_thai !== LichHen::STATUS_COMPLETED_VN) {
            $taiKham->lichHen->update(['trang_thai' => LichHen::STATUS_CANCELLED_VN]);
        }

        if ($taiKham->benhAn) {
            BenhAnObserver::logCustomActionWithValues(
                $taiKham->benhAn,
                'tai_kham_cancelled_by_patient',
                ['tai_kham' => $before],
                ['tai_kham' => $taiKham->toArray()]
            );
        }

        $doctorUser = $taiKham->bacSi?->user;
        if ($doctorUser) {
            $doctorUser->notify(new CustomNotification(
                'Bệnh nhân hủy tái khám',
                'Yêu cầu tái khám #' . $taiKham->id . ' đã bị bệnh nhân hủy.',
                route('doctor.taikham.show', $taiKham)
            ));
        }

        return back()->with('success', 'Đã hủy yêu cầu tái khám');
    }
}

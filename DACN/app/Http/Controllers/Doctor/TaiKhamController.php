<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\BenhAn;
use App\Models\TaiKham;
use App\Notifications\CustomNotification;
use App\Observers\BenhAnObserver;
use Illuminate\Http\Request;

class TaiKhamController extends Controller
{
    public function index(Request $request)
    {
        $bacSi = auth()->user()->bacSi;

        if (!$bacSi) {
            abort(403, 'Tài khoản bác sĩ chưa được gắn hồ sơ bác sĩ');
        }

        $query = TaiKham::with(['benhAn.user', 'benhAn.lichHen', 'lichHen'])
            ->whereHas('benhAn', function ($q) use ($bacSi) {
                $q->where('bac_si_id', $bacSi->id);
            })
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('trang_thai', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $query->whereHas('benhAn.user', function ($u) use ($search) {
                $u->where('name', 'like', '%' . $search . '%')
                    ->orWhere('ho_ten', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('so_dien_thoai', 'like', '%' . $search . '%');
            });
        }

        $records = $query->paginate(20);

        $baseStatsQuery = TaiKham::whereHas('benhAn', function ($q) use ($bacSi) {
            $q->where('bac_si_id', $bacSi->id);
        });

        $stats = [
            'total' => (clone $baseStatsQuery)->count(),
            'pending' => (clone $baseStatsQuery)->where('trang_thai', TaiKham::STATUS_PENDING_VN)->count(),
            'confirmed' => (clone $baseStatsQuery)->where('trang_thai', TaiKham::STATUS_CONFIRMED_VN)->count(),
            'booked' => (clone $baseStatsQuery)->where('trang_thai', TaiKham::STATUS_BOOKED_VN)->count(),
            'completed' => (clone $baseStatsQuery)->where('trang_thai', TaiKham::STATUS_COMPLETED_VN)->count(),
            'cancelled' => (clone $baseStatsQuery)->where('trang_thai', TaiKham::STATUS_CANCELLED_VN)->count(),
        ];

        return view('doctor.taikham.index', compact('records', 'stats'));
    }

    public function create(BenhAn $benhAn)
    {
        $bacSi = auth()->user()->bacSi;
        if (!$bacSi || (int) $benhAn->bac_si_id !== (int) $bacSi->id) {
            abort(403, 'Bạn không có quyền tạo tái khám cho bệnh án này');
        }

        return view('doctor.taikham.create', compact('benhAn'));
    }

    public function store(Request $request, BenhAn $benhAn)
    {
        $bacSi = auth()->user()->bacSi;
        if (!$bacSi || (int) $benhAn->bac_si_id !== (int) $bacSi->id) {
            abort(403);
        }

        // Chống trùng yêu cầu đang mở cho cùng bệnh án
        $openStatuses = [TaiKham::STATUS_PENDING_VN, TaiKham::STATUS_CONFIRMED_VN, TaiKham::STATUS_BOOKED_VN];
        $existingOpen = TaiKham::where('benh_an_id', $benhAn->id)
            ->whereIn('trang_thai', $openStatuses)
            ->exists();
        if ($existingOpen) {
            return back()->with('error', 'Bệnh án này đã có yêu cầu tái khám đang xử lý. Vui lòng hoàn tất hoặc hủy yêu cầu hiện tại trước.');
        }

        $data = $request->validate([
            'ngay_tai_kham' => 'nullable|date',
            'thoi_gian_tai_kham' => 'nullable|date_format:H:i',
            'so_ngay_du_kien' => 'nullable|integer|min:1|max:365',
            'ly_do' => 'nullable|string|max:5000',
            'ghi_chu' => 'nullable|string|max:5000',
        ]);

        $record = TaiKham::create([
            'benh_an_id' => $benhAn->id,
            'user_id' => $benhAn->user_id,
            'bac_si_id' => $bacSi->id,
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
            'tai_kham_created_by_doctor',
            null,
            ['tai_kham' => $record->toArray()]
        );

        // Thông báo in-app cho bệnh nhân
        if ($benhAn->user) {
            $benhAn->user->notify(new CustomNotification(
                'Bác sĩ đã tạo yêu cầu tái khám',
                'Bạn có yêu cầu tái khám mới (#' . $record->id . ').',
                route('patient.taikham.show', $record)
            ));
        }

        return redirect()->route('doctor.taikham.show', $record)->with('success', 'Đã tạo yêu cầu tái khám');
    }

    public function show(TaiKham $taiKham)
    {
        $taiKham->loadMissing(['benhAn.user', 'benhAn.lichHen', 'lichHen']);
        $this->authorize('view', $taiKham);

        return view('doctor.taikham.show', ['record' => $taiKham]);
    }

    public function update(Request $request, TaiKham $taiKham)
    {
        $this->authorize('update', $taiKham);

        $taiKham->loadMissing(['lichHen']);

        $data = $request->validate([
            'ngay_tai_kham' => 'nullable|date',
            'thoi_gian_tai_kham' => 'nullable|date_format:H:i',
            'so_ngay_du_kien' => 'nullable|integer|min:1|max:365',
            'ghi_chu' => 'nullable|string|max:5000',
            'trang_thai' => 'nullable|string|in:'
                . TaiKham::STATUS_PENDING_VN . ','
                . TaiKham::STATUS_CONFIRMED_VN . ','
                . TaiKham::STATUS_COMPLETED_VN . ','
                . TaiKham::STATUS_CANCELLED_VN,
        ]);

        $taiKham->update([
            'ngay_tai_kham' => array_key_exists('ngay_tai_kham', $data) ? $data['ngay_tai_kham'] : $taiKham->ngay_tai_kham,
            'thoi_gian_tai_kham' => array_key_exists('thoi_gian_tai_kham', $data) ? $data['thoi_gian_tai_kham'] : $taiKham->thoi_gian_tai_kham,
            'so_ngay_du_kien' => array_key_exists('so_ngay_du_kien', $data) ? $data['so_ngay_du_kien'] : $taiKham->so_ngay_du_kien,
            'ghi_chu' => array_key_exists('ghi_chu', $data) ? $data['ghi_chu'] : $taiKham->ghi_chu,
            'trang_thai' => array_key_exists('trang_thai', $data) ? $data['trang_thai'] : $taiKham->trang_thai,
        ]);

        // Nếu bác sĩ hủy và có lịch hẹn liên kết thì hủy lịch hẹn
        if ($taiKham->trang_thai === TaiKham::STATUS_CANCELLED_VN && $taiKham->lichHen && $taiKham->lichHen->trang_thai !== \App\Models\LichHen::STATUS_COMPLETED_VN) {
            $taiKham->lichHen->update(['trang_thai' => \App\Models\LichHen::STATUS_CANCELLED_VN]);
        }

        return back()->with('success', 'Đã cập nhật tái khám');
    }
}

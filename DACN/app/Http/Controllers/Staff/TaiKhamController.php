<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\DichVu;
use App\Models\HoaDon;
use App\Models\LichHen;
use App\Models\TaiKham;
use App\Notifications\CustomNotification;
use App\Observers\BenhAnObserver;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TaiKhamController extends Controller
{
    public function index(Request $request)
    {
        $query = TaiKham::with(['benhAn.user', 'benhAn.bacSi.user', 'lichHen'])
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('trang_thai', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', '%' . $search . '%')
                    ->orWhereHas('benhAn.user', function ($u) use ($search) {
                        $u->where('name', 'like', '%' . $search . '%')
                            ->orWhere('ho_ten', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%')
                            ->orWhere('so_dien_thoai', 'like', '%' . $search . '%');
                    });
            });
        }

        $records = $query->paginate(20);

        $stats = [
            'pending' => TaiKham::where('trang_thai', TaiKham::STATUS_PENDING_VN)->count(),
            'confirmed' => TaiKham::where('trang_thai', TaiKham::STATUS_CONFIRMED_VN)->count(),
            'booked' => TaiKham::where('trang_thai', TaiKham::STATUS_BOOKED_VN)->count(),
            'completed' => TaiKham::where('trang_thai', TaiKham::STATUS_COMPLETED_VN)->count(),
            'cancelled' => TaiKham::where('trang_thai', TaiKham::STATUS_CANCELLED_VN)->count(),
        ];

        return view('staff.taikham.index', compact('records', 'stats'));
    }

    public function show(TaiKham $taiKham)
    {
        $taiKham->loadMissing(['benhAn.user', 'benhAn.bacSi.user', 'benhAn.lichHen', 'lichHen', 'user', 'bacSi.user']);
        $this->authorize('view', $taiKham);

        $suggestedDichVuId = $taiKham->benhAn?->lichHen?->dich_vu_id;
        $dichVus = null;
        if (!$suggestedDichVuId) {
            $dichVus = DichVu::orderBy('ten_dich_vu')->get(['id', 'ten_dich_vu']);
        }

        return view('staff.taikham.show', [
            'record' => $taiKham,
            'suggestedDichVuId' => $suggestedDichVuId,
            'dichVus' => $dichVus,
        ]);
    }

    public function book(Request $request, TaiKham $taiKham)
    {
        $this->authorize('update', $taiKham);

        $taiKham->loadMissing(['benhAn.lichHen', 'benhAn.user', 'user', 'bacSi.user']);

        if ($taiKham->is_locked) {
            return back()->with('error', 'Yêu cầu đã bị khóa chỉnh sửa');
        }

        if ($taiKham->lich_hen_id) {
            return back()->with('info', 'Yêu cầu này đã được đặt lịch');
        }

        $suggestedDichVuId = $taiKham->benhAn?->lichHen?->dich_vu_id;

        $data = $request->validate([
            'ngay_hen' => 'required|date|after_or_equal:today',
            'thoi_gian_hen' => 'required|date_format:H:i',
            'dich_vu_id' => $suggestedDichVuId ? 'nullable|integer|exists:dich_vus,id' : 'required|integer|exists:dich_vus,id',
            'ghi_chu' => 'nullable|string|max:5000',
        ], [
            'ngay_hen.required' => 'Vui lòng chọn ngày hẹn',
            'thoi_gian_hen.required' => 'Vui lòng chọn giờ hẹn',
            'dich_vu_id.required' => 'Không xác định dịch vụ, vui lòng chọn dịch vụ',
        ]);

        $userId = $taiKham->user_id ?? $taiKham->benhAn?->user_id;
        $bacSiId = $taiKham->bac_si_id ?? $taiKham->benhAn?->bac_si_id;
        $dichVuId = $suggestedDichVuId ?: (int) $data['dich_vu_id'];

        if (!$userId || !$bacSiId || !$dichVuId) {
            return back()->with('error', 'Thiếu dữ liệu để tạo lịch hẹn (bệnh nhân/bác sĩ/dịch vụ)');
        }

        // Chống đè lịch: cùng bác sĩ + ngày + giờ đã có lịch hẹn (trừ đã hủy)
        $existing = LichHen::where('bac_si_id', $bacSiId)
            ->whereDate('ngay_hen', $data['ngay_hen'])
            ->where('thoi_gian_hen', $data['thoi_gian_hen'])
            ->where('trang_thai', '!=', LichHen::STATUS_CANCELLED_VN)
            ->first();
        if ($existing) {
            return back()->withErrors(['thoi_gian_hen' => 'Khung giờ này đã có lịch hẹn. Vui lòng chọn giờ khác.'])->withInput();
        }

        $benhAn = $taiKham->benhAn;
        $before = $taiKham->toArray();

        try {
            DB::transaction(function () use (&$taiKham, $data, $userId, $bacSiId, $dichVuId) {
                $dichVu = DichVu::find($dichVuId);
                $tongTien = $dichVu?->gia ?? 0;

                $ghiChu = trim((string)($data['ghi_chu'] ?? ''));
                if ($ghiChu === '') {
                    $ghiChu = 'Đặt lịch tái khám #' . $taiKham->id;
                }

                $lichHen = LichHen::create([
                    'user_id' => $userId,
                    'bac_si_id' => $bacSiId,
                    'dich_vu_id' => $dichVuId,
                    'tong_tien' => $tongTien,
                    'ngay_hen' => $data['ngay_hen'],
                    'thoi_gian_hen' => $data['thoi_gian_hen'],
                    'ghi_chu' => $ghiChu,
                    'trang_thai' => LichHen::STATUS_CONFIRMED_VN,
                ]);

                if (!HoaDon::where('lich_hen_id', $lichHen->id)->exists()) {
                    HoaDon::create([
                        'lich_hen_id' => $lichHen->id,
                        'user_id' => $userId,
                        'tong_tien' => $tongTien,
                        'trang_thai' => HoaDon::STATUS_UNPAID_VN,
                        'ghi_chu' => 'Tạo từ yêu cầu tái khám #' . $taiKham->id,
                    ]);
                }

                $taiKham->update([
                    'lich_hen_id' => $lichHen->id,
                    'trang_thai' => TaiKham::STATUS_BOOKED_VN,
                ]);
            });
        } catch (\Throwable $e) {
            return back()->with('error', 'Không thể đặt lịch tái khám: ' . $e->getMessage());
        }

        $taiKham->refresh();

        if ($benhAn) {
            BenhAnObserver::logCustomActionWithValues(
                $benhAn,
                'tai_kham_booked',
                ['tai_kham' => $before],
                ['tai_kham' => $taiKham->toArray()]
            );
        }

        $patient = $taiKham->user;
        if ($patient) {
            $patient->notify(new CustomNotification(
                'Đã đặt lịch tái khám',
                'Yêu cầu tái khám #' . $taiKham->id . ' đã được đặt lịch.',
                route('patient.taikham.show', $taiKham)
            ));
        }

        $doctorUser = $taiKham->bacSi?->user;
        if ($doctorUser) {
            $doctorUser->notify(new CustomNotification(
                'Có lịch tái khám mới',
                'Yêu cầu tái khám #' . $taiKham->id . ' đã được đặt lịch.',
                route('doctor.taikham.show', $taiKham)
            ));
        }

        return back()->with('success', 'Đã tạo lịch hẹn và liên kết tái khám');
    }

    public function update(Request $request, TaiKham $taiKham)
    {
        $this->authorize('update', $taiKham);

        $taiKham->loadMissing(['benhAn', 'user', 'bacSi.user', 'lichHen']);

        $before = $taiKham->toArray();

        $data = $request->validate([
            'trang_thai' => 'required|string|in:'
                . TaiKham::STATUS_PENDING_VN . ','
                . TaiKham::STATUS_CONFIRMED_VN . ','
                . TaiKham::STATUS_BOOKED_VN . ','
                . TaiKham::STATUS_COMPLETED_VN . ','
                . TaiKham::STATUS_CANCELLED_VN,
            'ghi_chu' => 'nullable|string|max:5000',
            'lich_hen_id' => 'nullable|integer|exists:lich_hens,id',
        ]);

        if (($data['trang_thai'] ?? null) === TaiKham::STATUS_BOOKED_VN && empty($data['lich_hen_id']) && empty($taiKham->lich_hen_id)) {
            return back()->withErrors(['lich_hen_id' => 'Trạng thái "Đã đặt lịch" yêu cầu phải có lịch hẹn liên kết.'])->withInput();
        }

        $taiKham->update([
            'trang_thai' => $data['trang_thai'],
            'ghi_chu' => $data['ghi_chu'] ?? $taiKham->ghi_chu,
            'lich_hen_id' => $data['lich_hen_id'] ?? $taiKham->lich_hen_id,
        ]);

        // Sync trạng thái hủy về lịch hẹn nếu có
        if ($taiKham->trang_thai === TaiKham::STATUS_CANCELLED_VN && $taiKham->lichHen && $taiKham->lichHen->trang_thai !== LichHen::STATUS_COMPLETED_VN) {
            $taiKham->lichHen->update(['trang_thai' => LichHen::STATUS_CANCELLED_VN]);
        }

        if ($taiKham->benhAn) {
            BenhAnObserver::logCustomActionWithValues(
                $taiKham->benhAn,
                'tai_kham_updated_by_staff',
                ['tai_kham' => $before],
                ['tai_kham' => $taiKham->toArray()]
            );
        }

        // Notifications khi đổi trạng thái
        $patient = $taiKham->user;
        if ($patient) {
            $patient->notify(new CustomNotification(
                'Cập nhật tái khám',
                'Yêu cầu tái khám #' . $taiKham->id . ' đã được cập nhật: ' . $taiKham->trang_thai,
                route('patient.taikham.show', $taiKham)
            ));
        }

        $doctorUser = $taiKham->bacSi?->user;
        if ($doctorUser) {
            $doctorUser->notify(new CustomNotification(
                'Cập nhật tái khám',
                'Yêu cầu tái khám #' . $taiKham->id . ' đã được cập nhật: ' . $taiKham->trang_thai,
                route('doctor.taikham.show', $taiKham)
            ));
        }

        return back()->with('success', 'Đã cập nhật trạng thái tái khám');
    }
}

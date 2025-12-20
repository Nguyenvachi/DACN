<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\TheoDoiThaiKy;
use App\Notifications\PregnancyTrackingStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TheoDoiThaiKyController extends Controller
{
    public function index(Request $request)
    {
        $query = TheoDoiThaiKy::with(['benhAn.user', 'benhAn.lichHen', 'benhAn.bacSi.user'])
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
                            ->orWhere('email', 'like', '%' . $search . '%')
                            ->orWhere('so_dien_thoai', 'like', '%' . $search . '%')
                            ->orWhere('ho_ten', 'like', '%' . $search . '%');
                    });
            });
        }

        $records = $query->paginate(20);

        $stats = [
            'submitted' => TheoDoiThaiKy::where('trang_thai', TheoDoiThaiKy::STATUS_SUBMITTED)->count(),
            'reviewed' => TheoDoiThaiKy::where('trang_thai', TheoDoiThaiKy::STATUS_REVIEWED)->count(),
            'recorded' => TheoDoiThaiKy::where('trang_thai', TheoDoiThaiKy::STATUS_RECORDED)->count(),
            'archived' => TheoDoiThaiKy::where('trang_thai', TheoDoiThaiKy::STATUS_ARCHIVED)->count(),
        ];

        return view('staff.theodoithaiky.index', compact('records', 'stats'));
    }

    public function show(TheoDoiThaiKy $theoDoiThaiKy)
    {
        $theoDoiThaiKy->loadMissing(['benhAn.user', 'benhAn.lichHen', 'benhAn.bacSi.user']);
        $this->authorize('view', $theoDoiThaiKy);

        return view('staff.theodoithaiky.show', ['record' => $theoDoiThaiKy]);
    }

    public function update(Request $request, TheoDoiThaiKy $theoDoiThaiKy)
    {
        $this->authorize('update', $theoDoiThaiKy);

        $oldStatus = $theoDoiThaiKy->trang_thai;

        $data = $request->validate([
            'trang_thai' => 'required|string|in:submitted,reviewed,recorded,archived',
            'ghi_chu' => 'nullable|string|max:5000',
        ]);

        $theoDoiThaiKy->update([
            'trang_thai' => $data['trang_thai'],
            'ghi_chu' => $data['ghi_chu'] ?? $theoDoiThaiKy->ghi_chu,
        ]);

        // THÊM: thông báo cho bệnh nhân khi đổi trạng thái (staff hỗ trợ vận hành)
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
                    'Nhân viên đã cập nhật trạng thái bản ghi theo dõi thai kỳ của bạn.',
                    $actionUrl
                ));
            }
        } catch (\Throwable $e) {
            Log::warning('Pregnancy tracking status update (staff) notification skipped', [
                'theo_doi_thai_ky_id' => $theoDoiThaiKy->id,
                'error' => $e->getMessage(),
            ]);
        }

        return back()->with('success', 'Đã cập nhật trạng thái');
    }
}

<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\DonThuoc;
use App\Observers\BenhAnObserver;
use Illuminate\Http\Request;

class DonThuocController extends Controller
{
    /**
     * Danh sách đơn thuốc chờ cấp
     */
    public function pending(Request $request)
    {
        $query = DonThuoc::with(['benhAn.user', 'benhAn.bacSi.user', 'items.thuoc'])
            ->choCapThuoc()
            ->orderByDesc('created_at');

        if ($request->filled('q')) {
            $keyword = trim((string) $request->q);
            $query->whereHas('benhAn.user', function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%");
            });
        }

        $donThuocs = $query->paginate(20);

        return view('staff.donthuoc.pending', compact('donThuocs'));
    }

    /**
     * Danh sách đơn thuốc đã cấp
     */
    public function completed(Request $request)
    {
        $query = DonThuoc::with(['benhAn.user', 'benhAn.bacSi.user', 'items.thuoc', 'nguoiCapThuoc'])
            ->where('trang_thai', DonThuoc::STATUS_DA_CAP_THUOC)
            ->orderByDesc('ngay_cap_thuoc');

        if ($request->filled('q')) {
            $keyword = trim((string) $request->q);
            $query->whereHas('benhAn.user', function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%");
            });
        }

        $donThuocs = $query->paginate(20);

        return view('staff.donthuoc.completed', compact('donThuocs'));
    }

    /**
     * Xem chi tiết đơn thuốc
     */
    public function show(DonThuoc $donThuoc)
    {
        $donThuoc->load(['benhAn.user', 'benhAn.bacSi.user', 'items.thuoc', 'nguoiCapThuoc']);

        return view('staff.donthuoc.show', compact('donThuoc'));
    }

    /**
     * Xác nhận cấp thuốc
     */
    public function dispense(Request $request, DonThuoc $donThuoc)
    {
        $data = $request->validate([
            'ghi_chu_cap_thuoc' => 'nullable|string|max:1000',
        ]);

        if ($donThuoc->trang_thai === DonThuoc::STATUS_DA_CAP_THUOC) {
            return back()->with('status', 'Đơn thuốc này đã được cấp trước đó.');
        }

        $donThuoc->update([
            'trang_thai' => DonThuoc::STATUS_DA_CAP_THUOC,
            'ngay_cap_thuoc' => now(),
            'nguoi_cap_thuoc_id' => auth()->id(),
            'ghi_chu_cap_thuoc' => $data['ghi_chu_cap_thuoc'] ?? null,
        ]);

        if ($donThuoc->benhAn) {
            BenhAnObserver::logCustomAction(
                $donThuoc->benhAn,
                'medicine_dispensed',
                "Cấp thuốc cho đơn #{$donThuoc->id}"
            );
        }

        return redirect()
            ->route('staff.donthuoc.show', $donThuoc)
            ->with('success', 'Đã xác nhận cấp thuốc thành công.');
    }
}

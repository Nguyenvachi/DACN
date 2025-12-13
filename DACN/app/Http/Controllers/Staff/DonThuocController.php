<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\DonThuoc;
use App\Models\DonThuocItem;
use App\Models\BenhAn;
use App\Models\Thuoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Controller quản lý Đơn thuốc cho Nhân viên
 * Nhân viên xem đơn thuốc của bác sĩ và chuẩn bị thuốc cho bệnh nhân
 */
class DonThuocController extends Controller
{
    /**
     * Danh sách tất cả đơn thuốc
     */
    public function index(Request $request)
    {
        $query = DonThuoc::with(['benhAn.user', 'benhAn.bacSi.user', 'items.thuoc'])
            ->orderBy('created_at', 'desc');

        // Lọc theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->whereHas('benhAn', function ($q) use ($request) {
                $q->where('trang_thai', $request->trang_thai);
            });
        }

        // Tìm kiếm theo tên bệnh nhân
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('benhAn.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('so_dien_thoai', 'like', "%{$search}%");
            });
        }

        // Lọc theo ngày
        if ($request->filled('tu_ngay')) {
            $query->whereDate('created_at', '>=', $request->tu_ngay);
        }
        if ($request->filled('den_ngay')) {
            $query->whereDate('created_at', '<=', $request->den_ngay);
        }

        $donThuocs = $query->paginate(20);

        return view('staff.donthuoc.index', compact('donThuocs'));
    }

    /**
     * Xem chi tiết đơn thuốc
     */
    public function show(DonThuoc $donThuoc)
    {
        $donThuoc->load(['benhAn.user', 'benhAn.bacSi.user', 'items.thuoc']);

        return view('staff.donthuoc.show', compact('donThuoc'));
    }

    /**
     * Xem đơn thuốc từ bệnh án
     */
    public function showFromBenhAn(BenhAn $benhAn)
    {
        $donThuoc = $benhAn->donThuoc()->with(['items.thuoc'])->first();

        if (!$donThuoc) {
            return redirect()->route('staff.donthuoc.index')
                ->with('error', 'Bệnh án này chưa có đơn thuốc.');
        }

        return view('staff.donthuoc.show', compact('donThuoc'));
    }

    /**
     * Đánh dấu đã cấp thuốc
     */
    public function capThuoc(Request $request, DonThuoc $donThuoc)
    {
        $request->validate([
            'ghi_chu_cap_thuoc' => 'nullable|string|max:500',
        ]);

        try {
            // Cập nhật thông tin cấp thuốc
            $donThuoc->update([
                'ngay_cap_thuoc' => now(),
                'nguoi_cap_thuoc_id' => auth()->id(),
                'ghi_chu_cap_thuoc' => $request->ghi_chu_cap_thuoc,
            ]);

            // TODO: Trừ tồn kho thuốc nếu có hệ thống quản lý kho
            // foreach ($donThuoc->items as $item) {
            //     // Logic trừ kho
            // }

            return redirect()->route('staff.donthuoc.show', $donThuoc)
                ->with('success', 'Đã đánh dấu cấp thuốc thành công! Bạn có thể tạo hóa đơn và thanh toán.');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Hủy cấp thuốc (nếu cấp nhầm)
     */
    public function huyCap(DonThuoc $donThuoc)
    {
        try {
            // Xóa thông tin cấp thuốc
            $donThuoc->update([
                'ngay_cap_thuoc' => null,
                'nguoi_cap_thuoc_id' => null,
                'ghi_chu_cap_thuoc' => null,
            ]);

            return redirect()->route('staff.donthuoc.show', $donThuoc)
                ->with('success', 'Đã hủy cấp thuốc!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * API: Kiểm tra tồn kho thuốc
     */
    public function checkTonKho(Thuoc $thuoc)
    {
        $tonKho = $thuoc->tongTonKho();

        return response()->json([
            'thuoc_id' => $thuoc->id,
            'ten' => $thuoc->ten,
            'ton_kho' => $tonKho,
            'ton_toi_thieu' => $thuoc->ton_toi_thieu,
            'canh_bao' => $tonKho <= $thuoc->ton_toi_thieu,
        ]);
    }

    /**
     * Danh sách đơn thuốc đang chờ cấp
     */
    public function dangCho()
    {
        $donThuocs = DonThuoc::with(['benhAn.user', 'benhAn.bacSi.user', 'items.thuoc'])
            ->whereNull('ngay_cap_thuoc')
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        return view('staff.donthuoc.dang-cho', compact('donThuocs'));
    }

    /**
     * Danh sách đơn thuốc đã cấp
     */
    public function daCap()
    {
        $donThuocs = DonThuoc::with(['benhAn.user', 'benhAn.bacSi.user', 'items.thuoc'])
            ->whereNotNull('ngay_cap_thuoc')
            ->orderBy('ngay_cap_thuoc', 'desc')
            ->paginate(20);

        return view('staff.donthuoc.da-cap', compact('donThuocs'));
    }
}

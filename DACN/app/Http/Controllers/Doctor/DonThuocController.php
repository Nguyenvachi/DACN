<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\DonThuoc;
use App\Models\DonThuocItem;
use App\Models\BenhAn;
use App\Models\Thuoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Controller quản lý Đơn thuốc của Bác sĩ
 * Theo workflow: Bác sĩ khám xong → Kê đơn thuốc → Bệnh nhân mua tại quầy
 */
class DonThuocController extends Controller
{
    /**
     * Hiển thị form kê đơn thuốc cho một bệnh án
     */
    public function create(BenhAn $benhAn)
    {
        // Verify bác sĩ đang đăng nhập là bác sĩ của bệnh án này
        $bacSi = auth()->user()->bacSi;
        if (!$bacSi || $benhAn->bac_si_id !== $bacSi->id) {
            abort(403, 'Bạn không có quyền kê đơn cho bệnh án này');
        }

        // Kiểm tra trạng thái lịch hẹn
        $lichHen = $benhAn->lichHen;
        if (!$lichHen || !in_array($lichHen->trang_thai, ['Đang khám', 'Hoàn thành'])) {
            return redirect()->back()->with('error', 'Chỉ kê đơn khi đang khám hoặc đã hoàn thành');
        }

        // Lấy danh sách thuốc (có tồn kho > 0)
        $thuocs = Thuoc::orderBy('ten', 'asc')->get();

        // Kiểm tra đã có đơn thuốc chưa
        $existingDonThuoc = DonThuoc::where('benh_an_id', $benhAn->id)->with('items.thuoc')->first();

        return view('doctor.donthuoc.create', compact('benhAn', 'thuocs', 'existingDonThuoc'));
    }

    /**
     * Lưu đơn thuốc mới
     */
    public function store(Request $request, BenhAn $benhAn)
    {
        $bacSi = auth()->user()->bacSi;
        if (!$bacSi || $benhAn->bac_si_id !== $bacSi->id) {
            abort(403, 'Không có quyền');
        }

        $request->validate([
            'thuocs' => 'required|array|min:1',
            'thuocs.*.thuoc_id' => 'required|exists:thuocs,id',
            'thuocs.*.so_luong' => 'required|integer|min:1',
            'thuocs.*.lieu_dung' => 'required|string|max:255',
            'thuocs.*.cach_dung' => 'nullable|string|max:500',
            'ghi_chu' => 'nullable|string|max:1000',
        ], [
            'thuocs.required' => 'Vui lòng chọn ít nhất 1 loại thuốc',
            'thuocs.*.thuoc_id.required' => 'Thiếu thông tin thuốc',
            'thuocs.*.so_luong.required' => 'Vui lòng nhập số lượng',
            'thuocs.*.so_luong.min' => 'Số lượng phải lớn hơn 0',
            'thuocs.*.lieu_dung.required' => 'Vui lòng nhập liều dùng',
        ]);

        try {
            DB::beginTransaction();

            // Kiểm tra đã có đơn thuốc chưa
            $donThuoc = DonThuoc::where('benh_an_id', $benhAn->id)->first();

            if ($donThuoc) {
                // Cập nhật ghi chú
                $donThuoc->update(['ghi_chu' => $request->ghi_chu]);

                // Xóa items cũ và tạo mới
                $donThuoc->items()->delete();
            } else {
                // Tạo đơn thuốc mới
                $donThuoc = DonThuoc::create([
                    'benh_an_id' => $benhAn->id,
                    'user_id' => $benhAn->user_id,
                    'bac_si_id' => $bacSi->id,
                    'lich_hen_id' => $benhAn->lich_hen_id,
                    'ghi_chu' => $request->ghi_chu,
                ]);
            }

            // Tạo items
            foreach ($request->thuocs as $thuocData) {
                DonThuocItem::create([
                    'don_thuoc_id' => $donThuoc->id,
                    'thuoc_id' => $thuocData['thuoc_id'],
                    'so_luong' => $thuocData['so_luong'],
                    'lieu_dung' => $thuocData['lieu_dung'],
                    'cach_dung' => $thuocData['cach_dung'] ?? null,
                ]);
            }

            DB::commit();

            Log::info("Bác sĩ #{$bacSi->id} đã kê đơn thuốc #{$donThuoc->id} cho bệnh án #{$benhAn->id}");

            return redirect()
                ->route('doctor.benhan.edit', $benhAn->id)
                ->with('success', 'Đã kê đơn thuốc thành công! Bệnh nhân có thể mua tại quầy thuốc.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Lỗi khi kê đơn thuốc: " . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Xem chi tiết đơn thuốc (để in)
     */
    public function show(DonThuoc $donThuoc)
    {
        $bacSi = auth()->user()->bacSi;

        // Bác sĩ chỉ xem được đơn thuốc của mình
        if ($donThuoc->bac_si_id !== $bacSi->id) {
            abort(403, 'Không có quyền xem đơn thuốc này');
        }

        $donThuoc->load(['items.thuoc', 'benhAn.user', 'benhAn.lichHen']);

        return view('doctor.donthuoc.show', compact('donThuoc'));
    }

    /**
     * Xóa đơn thuốc (chỉ khi chưa xuất kho)
     */
    public function destroy(DonThuoc $donThuoc)
    {
        $bacSi = auth()->user()->bacSi;

        if ($donThuoc->bac_si_id !== $bacSi->id) {
            abort(403);
        }

        // TODO: Kiểm tra đơn thuốc đã xuất kho chưa
        // if ($donThuoc->trang_thai === 'Đã xuất') {
        //     return back()->with('error', 'Không thể xóa đơn thuốc đã xuất kho');
        // }

        DB::transaction(function () use ($donThuoc) {
            $donThuoc->items()->delete();
            $donThuoc->delete();
        });

        Log::info("Đã xóa đơn thuốc #{$donThuoc->id}");

        return redirect()
            ->route('doctor.benhan.edit', $donThuoc->benh_an_id)
            ->with('success', 'Đã xóa đơn thuốc');
    }

    /**
     * API: Lấy thông tin thuốc theo ID
     */
    public function getThuocInfo(Thuoc $thuoc)
    {
        return response()->json([
            'id' => $thuoc->id,
            'ten' => $thuoc->ten,
            'hoat_chat' => $thuoc->hoat_chat,
            'ham_luong' => $thuoc->ham_luong,
            'don_vi' => $thuoc->don_vi,
            'ton_kho' => $thuoc->tongTonKho(),
            'gia' => $thuoc->gia_tham_khao,
        ]);
    }
}

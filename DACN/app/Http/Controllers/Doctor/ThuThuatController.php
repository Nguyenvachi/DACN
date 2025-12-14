<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\BacSi;
use App\Models\BenhAn;
use App\Models\ThuThuat;
use App\Models\DichVu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ThuThuatController extends Controller
{
    /**
     * Trang chỉ định thủ thuật
     */
    public function create(BenhAn $benhAn)
    {
        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi || $benhAn->bac_si_id !== $bacSi->id) {
            abort(403, 'Bạn không có quyền chỉ định thủ thuật cho bệnh án này.');
        }

        $dichVuThuThuat = DichVu::where('loai', 'Nâng cao')
            ->where('hoat_dong', true)
            ->where(function ($q) {
                $q->where('ten_dich_vu', 'like', '%chọc%')
                    ->orWhere('ten_dich_vu', 'like', '%sinh thiết%')
                    ->orWhere('ten_dich_vu', 'like', '%đo%');
            })
            ->orderBy('ten_dich_vu')
            ->get();

        $existingThuThuat = ThuThuat::where('benh_an_id', $benhAn->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('doctor.thu-thuat.create', compact('benhAn', 'dichVuThuThuat', 'existingThuThuat'));
    }

    /**
     * Lưu chỉ định thủ thuật
     */
    public function store(Request $request, BenhAn $benhAn)
    {
        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi || $benhAn->bac_si_id !== $bacSi->id) {
            return back()->with('error', 'Bạn không có quyền chỉ định thủ thuật cho bệnh án này.');
        }

        $validated = $request->validate([
            'dich_vu_id' => 'required|exists:dich_vus,id',
            'chi_tiet_truoc_thu_thuat' => 'nullable|string',
            'ghi_chu' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Lấy thông tin dịch vụ
            $dichVu = DichVu::findOrFail($validated['dich_vu_id']);

            $giaThuThuat = $dichVu->gia_tien ?? $dichVu->gia ?? 0;

            ThuThuat::create([
                'benh_an_id' => $benhAn->id,
                'bac_si_id' => $bacSi->id,
                'ten_thu_thuat' => $dichVu->ten_dich_vu,
                'ngay_chi_dinh' => now()->toDateString(),
                'gia_tien' => $giaThuThuat,
                'trang_thai' => 'Chờ thực hiện',
                'trang_thai_thanh_toan' => 'Chưa thanh toán',
                'chi_tiet_truoc_thu_thuat' => $validated['chi_tiet_truoc_thu_thuat'] ?? null,
                'ghi_chu' => $validated['ghi_chu'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('doctor.benhan.show', $benhAn->id)
                ->with('success', 'Đã chỉ định thủ thuật thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Trang nhập kết quả thủ thuật
     */
    public function edit(ThuThuat $thuThuat)
    {
        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi) {
            abort(403);
        }

        return view('doctor.thu-thuat.edit', compact('thuThuat'));
    }

    /**
     * Cập nhật kết quả thủ thuật
     */
    public function update(Request $request, ThuThuat $thuThuat)
    {
        $validated = $request->validate([
            'ngay_thuc_hien' => 'required|date',
            'trang_thai' => 'required|in:Chờ thực hiện,Đang thực hiện,Đã hoàn thành,Đã hủy',
            'mo_ta_quy_trinh' => 'nullable|string',
            'ket_qua' => 'nullable|string',
            'bien_chung' => 'nullable|string',
            'xu_tri' => 'nullable|string',
        ]);

        try {
            $thuThuat->update($validated);

            return redirect()->route('doctor.benhan.show', $thuThuat->benh_an_id)
                ->with('success', 'Đã cập nhật kết quả thủ thuật!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }
}

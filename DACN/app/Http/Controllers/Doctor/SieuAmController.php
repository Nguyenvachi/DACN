<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\BacSi;
use App\Models\BenhAn;
use App\Models\SieuAm;
use App\Models\DichVu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SieuAmController extends Controller
{
    /**
     * Trang chỉ định siêu âm cho bệnh án
     */
    public function create(BenhAn $benhAn)
    {
        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi || $benhAn->bac_si_id !== $bacSi->id) {
            abort(403, 'Bạn không có quyền chỉ định siêu âm cho bệnh án này.');
        }

        $dichVuSieuAm = DichVu::where('loai', 'Nâng cao')
            ->where('hoat_dong', true)
            ->where(function ($q) {
                $q->where('ten_dich_vu', 'like', '%siêu âm%')
                    ->orWhere('ten_dich_vu', 'like', '%Siêu âm%');
            })
            ->orderBy('ten_dich_vu')
            ->get();

        $existingSieuAm = SieuAm::where('benh_an_id', $benhAn->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('doctor.sieu-am.create', compact('benhAn', 'dichVuSieuAm', 'existingSieuAm'));
    }

    /**
     * Lưu chỉ định siêu âm
     */
    public function store(Request $request, BenhAn $benhAn)
    {
        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi || $benhAn->bac_si_id !== $bacSi->id) {
            return back()->with('error', 'Bạn không có quyền chỉ định siêu âm cho bệnh án này.');
        }

        $validated = $request->validate([
            'dich_vu_id' => 'required|exists:dich_vus,id',
            'ly_do_chi_dinh' => 'nullable|string',
            'ghi_chu' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Lấy thông tin dịch vụ
            $dichVu = DichVu::findOrFail($validated['dich_vu_id']);

            SieuAm::create([
                'benh_an_id' => $benhAn->id,
                'bac_si_id' => $bacSi->id,
                'loai_sieu_am' => $dichVu->ten_dich_vu,
                'ngay_chi_dinh' => now()->toDateString(),
                'gia_tien' => $dichVu->gia_tien,
                'ly_do_chi_dinh' => $validated['ly_do_chi_dinh'] ?? null,
                'trang_thai' => 'Chờ thực hiện',
                'trang_thai_thanh_toan' => 'Chưa thanh toán',
                'ghi_chu' => $validated['ghi_chu'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('doctor.benhan.show', $benhAn->id)
                ->with('success', 'Đã chỉ định siêu âm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Trang nhập kết quả siêu âm
     */
    public function edit(SieuAm $sieuAm)
    {
        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi) {
            abort(403);
        }

        return view('doctor.sieu-am.edit', compact('sieuAm'));
    }

    /**
     * Cập nhật kết quả siêu âm
     */
    public function update(Request $request, SieuAm $sieuAm)
    {
        $validated = $request->validate([
            'ngay_thuc_hien' => 'required|date',
            'trang_thai' => 'required|in:Chờ thực hiện,Đang thực hiện,Hoàn thành,Đã hủy',
            'ket_qua' => 'nullable|string',
            'nhan_xet' => 'nullable|string',
            'tuoi_thai_tuan' => 'nullable|integer|min:0|max:42',
            'tuoi_thai_ngay' => 'nullable|integer|min:0|max:6',
            'can_nang_uoc_tinh' => 'nullable|numeric|min:0',
            'chieu_dai_dau_mong' => 'nullable|numeric|min:0',
            'duong_kinh_hai_dinh' => 'nullable|numeric|min:0',
            'chu_vi_bung' => 'nullable|numeric|min:0',
            'chieu_dai_xuong_dui' => 'nullable|numeric|min:0',
            'luong_nuoc_oi' => 'nullable|numeric|min:0',
        ]);

        try {
            $sieuAm->update($validated);

            return redirect()->route('doctor.benhan.show', $sieuAm->benh_an_id)
                ->with('success', 'Đã cập nhật kết quả siêu âm!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }
}

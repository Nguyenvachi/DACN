<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\BacSi;
use App\Models\BenhAn;
use App\Models\XQuang;
use App\Models\DichVu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class XQuangController extends Controller
{
    /**
     * Trang chỉ định X-quang cho bệnh án
     */
    public function create($benhAnId)
    {
        $benhAn = BenhAn::findOrFail($benhAnId);

        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi || $benhAn->bac_si_id !== $bacSi->id) {
            abort(403, 'Bạn không có quyền chỉ định X-quang cho bệnh án này.');
        }

        return view('doctor.x-quang.create', compact('benhAn'));
    }

    /**
     * Lưu chỉ định X-quang
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'benh_an_id' => 'required|exists:benh_ans,id',
            'dich_vu_id' => 'nullable|exists:dich_vus,id',
            'loai_x_quang' => 'required|string|max:255',
            'vi_tri' => 'required|string|max:255',
            'ngay_chi_dinh' => 'required|date',
            'chi_dinh' => 'nullable|string',
        ]);

        $benhAn = BenhAn::findOrFail($validated['benh_an_id']);
        $bacSi = BacSi::where('user_id', Auth::id())->first();

        if (!$bacSi || $benhAn->bac_si_id !== $bacSi->id) {
            return back()->with('error', 'Bạn không có quyền chỉ định X-quang cho bệnh án này.');
        }

        try {
            DB::beginTransaction();

            XQuang::create([
                'benh_an_id' => $validated['benh_an_id'],
                'dich_vu_id' => $validated['dich_vu_id'] ?? null,
                'loai_x_quang' => $validated['loai_x_quang'],
                'vi_tri' => $validated['vi_tri'],
                'ngay_chi_dinh' => $validated['ngay_chi_dinh'],
                'bac_si_chi_dinh_id' => $bacSi->id,
                'chi_dinh' => $validated['chi_dinh'] ?? null,
                'trang_thai' => 'Chờ chụp',
            ]);

            DB::commit();

            return redirect()->route('doctor.benhan.edit', $benhAn->id)
                ->with('success', 'Đã chỉ định X-quang thành công!')
                ->with('show_quick_actions', true)
                ->with('benh_an_id', $benhAn->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Hiển thị chi tiết kết quả X-quang
     */
    public function show(XQuang $xQuang)
    {
        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi) {
            abort(403);
        }

        $xQuang->load(['benhAn.benhNhan', 'bacSiChiDinh', 'bacSiDocKetQua', 'dichVu']);
        return view('doctor.x-quang.show', compact('xQuang'));
    }

    /**
     * Trang nhập kết quả X-quang
     */
    public function edit(XQuang $xQuang)
    {
        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi) {
            abort(403);
        }

        return view('doctor.x-quang.edit', compact('xQuang'));
    }

    /**
     * Cập nhật kết quả X-quang
     */
    public function update(Request $request, XQuang $xQuang)
    {
        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi) {
            return back()->with('error', 'Unauthorized');
        }

        $validated = $request->validate([
            'ngay_chup' => 'required|date',
            'trang_thai' => 'required|in:Chờ chụp,Đã chụp,Đã có kết quả,Đã hủy',
            'ky_thuat' => 'nullable|string|max:255',
            'mo_ta_hinh_anh' => 'nullable|string',
            'tim_mach' => 'nullable|string',
            'phoi' => 'nullable|string',
            'xuong_khop' => 'nullable|string',
            'co_quan_khac' => 'nullable|string',
            'chan_doan' => 'nullable|string',
            'ket_luan' => 'nullable|string',
            'de_nghi' => 'nullable|string',
            'ghi_chu' => 'nullable|string',
            'hinh_anh.*' => 'nullable|image|max:5120', // 5MB
        ]);

        try {
            DB::beginTransaction();

            $data = $validated;
            $data['bac_si_doc_ket_qua_id'] = $bacSi->id;

            // Xử lý upload hình ảnh
            if ($request->hasFile('hinh_anh')) {
                $hinhAnhPaths = [];
                foreach ($request->file('hinh_anh') as $file) {
                    $path = $file->store('x-quang', 'public');
                    $hinhAnhPaths[] = $path;
                }
                $data['hinh_anh'] = $hinhAnhPaths;
            }

            $xQuang->update($data);

            DB::commit();

            return redirect()->route('doctor.benhan.show', $xQuang->benh_an_id)
                ->with('success', 'Đã cập nhật kết quả X-quang thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }
}

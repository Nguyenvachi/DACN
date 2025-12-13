<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\BacSi;
use App\Models\BenhAn;
use App\Models\NoiSoi;
use App\Models\DichVu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class NoiSoiController extends Controller
{
    /**
     * Trang chỉ định nội soi cho bệnh án
     */
    public function create(Request $request)
    {
        $benhAnId = $request->get('benh_an_id');
        $benhAn = BenhAn::findOrFail($benhAnId);

        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi || $benhAn->bac_si_id !== $bacSi->id) {
            abort(403, 'Bạn không có quyền chỉ định nội soi cho bệnh án này.');
        }

        $dichVuNoiSoi = DichVu::where('loai', 'Nâng cao')
            ->where('hoat_dong', true)
            ->where(function ($q) {
                $q->where('ten_dich_vu', 'like', '%nội soi%')
                    ->orWhere('ten_dich_vu', 'like', '%Nội soi%');
            })
            ->orderBy('ten_dich_vu')
            ->get();

        return view('doctor.noi-soi.create', compact('benhAn', 'dichVuNoiSoi'));
    }

    /**
     * Lưu chỉ định nội soi
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'benh_an_id' => 'required|exists:benh_ans,id',
            'dich_vu_id' => 'nullable|exists:dich_vus,id',
            'loai_noi_soi' => 'required|string|max:255',
            'ngay_chi_dinh' => 'required|date',
            'chi_dinh' => 'nullable|string',
            'chuan_bi' => 'nullable|string',
        ]);

        $benhAn = BenhAn::findOrFail($validated['benh_an_id']);
        $bacSi = BacSi::where('user_id', Auth::id())->first();

        if (!$bacSi || $benhAn->bac_si_id !== $bacSi->id) {
            return back()->with('error', 'Bạn không có quyền chỉ định nội soi cho bệnh án này.');
        }

        try {
            DB::beginTransaction();

            NoiSoi::create([
                'benh_an_id' => $validated['benh_an_id'],
                'dich_vu_id' => $validated['dich_vu_id'],
                'loai_noi_soi' => $validated['loai_noi_soi'],
                'ngay_chi_dinh' => $validated['ngay_chi_dinh'],
                'bac_si_chi_dinh_id' => $bacSi->id,
                'chi_dinh' => $validated['chi_dinh'] ?? null,
                'chuan_bi' => $validated['chuan_bi'] ?? null,
                'trang_thai' => 'Chờ thực hiện',
            ]);

            DB::commit();

            return redirect()->route('doctor.benhan.show', $benhAn->id)
                ->with('success', 'Đã chỉ định nội soi thành công!')
                ->with('show_quick_actions', true)
                ->with('benh_an_id', $benhAn->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Hiển thị chi tiết kết quả nội soi
     */
    public function show(NoiSoi $noiSoi)
    {
        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi) {
            abort(403);
        }

        $noiSoi->load(['benhAn.benhNhan', 'bacSiChiDinh', 'bacSiThucHien', 'dichVu']);
        return view('doctor.noi-soi.show', compact('noiSoi'));
    }

    /**
     * Trang nhập kết quả nội soi
     */
    public function edit(NoiSoi $noiSoi)
    {
        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi) {
            abort(403);
        }

        return view('doctor.noi-soi.edit', compact('noiSoi'));
    }

    /**
     * Cập nhật kết quả nội soi
     */
    public function update(Request $request, NoiSoi $noiSoi)
    {
        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi) {
            return back()->with('error', 'Unauthorized');
        }

        $validated = $request->validate([
            'ngay_thuc_hien' => 'required|date',
            'trang_thai' => 'required|in:Chờ thực hiện,Đang thực hiện,Hoàn thành,Đã hủy',
            'mo_ta_hinh_anh' => 'nullable|string',
            'ton_thuong' => 'nullable|string',
            'chan_doan' => 'nullable|string',
            'sinh_thiet' => 'nullable|string',
            'xu_tri' => 'nullable|string',
            'bien_chung' => 'nullable|string',
            'ket_luan' => 'nullable|string',
            'de_nghi' => 'nullable|string',
            'ghi_chu' => 'nullable|string',
            'hinh_anh.*' => 'nullable|image|max:5120', // 5MB
        ]);

        try {
            DB::beginTransaction();

            $data = $validated;
            $data['bac_si_thuc_hien_id'] = $bacSi->id;

            // Xử lý upload hình ảnh
            if ($request->hasFile('hinh_anh')) {
                $hinhAnhPaths = [];
                foreach ($request->file('hinh_anh') as $file) {
                    $path = $file->store('noi-soi', 'public');
                    $hinhAnhPaths[] = $path;
                }
                $data['hinh_anh'] = $hinhAnhPaths;
            }

            $noiSoi->update($data);

            DB::commit();

            return redirect()->route('doctor.benhan.show', $noiSoi->benh_an_id)
                ->with('success', 'Đã cập nhật kết quả nội soi thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }
}

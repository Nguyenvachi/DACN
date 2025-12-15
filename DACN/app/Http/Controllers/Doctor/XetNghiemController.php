<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\BacSi;
use App\Models\BenhAn;
use App\Models\XetNghiem;
use App\Models\DichVu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class XetNghiemController extends Controller
{
    /**
     * Danh sách xét nghiệm
     */
    public function index(Request $request)
    {
        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi) {
            abort(403);
        }

        $xetNghiems = XetNghiem::whereHas('benhAn', function ($q) use ($bacSi) {
            $q->where('bac_si_id', $bacSi->id);
        })
            ->with(['benhAn.user'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('doctor.xetnghiem.index', compact('xetNghiems'));
    }

    /**
     * Trang chỉ định xét nghiệm cho bệnh án
     */
    public function create(Request $request)
    {
        $benhAnId = $request->get('benh_an_id');
        $benhAn = BenhAn::findOrFail($benhAnId);

        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi || $benhAn->bac_si_id !== $bacSi->id) {
            abort(403, 'Bạn không có quyền chỉ định xét nghiệm cho bệnh án này.');
        }

        // Lấy danh mục xét nghiệm
        $danhMucXetNghiem = \App\Models\DanhMucXetNghiem::orderBy('ten_xet_nghiem')->get();

        return view('doctor.xet-nghiem.create', compact('benhAn', 'danhMucXetNghiem'));
    }

    /**
     * Lưu chỉ định xét nghiệm
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'benh_an_id' => 'required|exists:benh_ans,id',
            'danh_muc_xet_nghiem_id' => 'required|exists:danh_muc_xet_nghiem,id',
            'ngay_chi_dinh' => 'required|date',
            'chi_dinh' => 'nullable|string',
            'can_nhin_an' => 'nullable|boolean',
            'chuan_bi' => 'nullable|string',
        ]);

        $benhAn = BenhAn::findOrFail($validated['benh_an_id']);
        $bacSi = BacSi::where('user_id', Auth::id())->first();

        if (!$bacSi || $benhAn->bac_si_id !== $bacSi->id) {
            return back()->with('error', 'Bạn không có quyền chỉ định xét nghiệm cho bệnh án này.');
        }

        // Lấy thông tin từ danh mục
        $danhMuc = \App\Models\DanhMucXetNghiem::findOrFail($validated['danh_muc_xet_nghiem_id']);

        try {
            DB::beginTransaction();

            XetNghiem::create([
                'benh_an_id' => $validated['benh_an_id'],
                'dich_vu_id' => null,
                'loai_xet_nghiem' => 'Xét nghiệm',
                'ten_xet_nghiem' => $danhMuc->ten_xet_nghiem,
                'gia_tien' => $danhMuc->gia_tien,
                'ngay_chi_dinh' => $validated['ngay_chi_dinh'],
                'bac_si_chi_dinh_id' => $bacSi->id,
                'chi_dinh' => $validated['chi_dinh'] ?? null,
                'can_nhin_an' => $validated['can_nhin_an'] ?? false,
                'chuan_bi' => $validated['chuan_bi'] ?? null,
                'trang_thai' => 'Chờ lấy mẫu',
            ]);

            DB::commit();

            return redirect()->route('doctor.benhan.show', $benhAn->id)
                ->with('success', 'Đã chỉ định xét nghiệm thành công!')
                ->with('show_quick_actions', true)
                ->with('benh_an_id', $benhAn->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Hiển thị chi tiết kết quả xét nghiệm
     */
    public function show(XetNghiem $xetNghiem)
    {
        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi) {
            abort(403);
        }

        $xetNghiem->load(['benhAn.benhNhan', 'bacSiChiDinh', 'dichVu']);
        return view('doctor.xet-nghiem.show', compact('xetNghiem'));
    }

    /**
     * Trang nhập kết quả xét nghiệm
     */
    public function edit(XetNghiem $xetNghiem)
    {
        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi) {
            abort(403);
        }

        $xetNghiem->load(['benhAn.user']);
        return view('doctor.xet-nghiem.edit', compact('xetNghiem'));
    }

    /**
     * Cập nhật kết quả xét nghiệm
     */
    public function update(Request $request, XetNghiem $xetNghiem)
    {
        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi) {
            return back()->with('error', 'Unauthorized');
        }

        $validated = $request->validate([
            'ngay_tra_ket_qua' => 'nullable|date',
            'ngay_lay_mau' => 'nullable|date',
            'chi_so' => 'nullable|array',
            'chi_so.*.ten' => 'required|string',
            'chi_so.*.ket_qua' => 'required',
            'chi_so.*.don_vi' => 'nullable|string',
            'chi_so.*.gia_tri_bt' => 'nullable|string',
            'chi_so.*.min' => 'nullable|numeric',
            'chi_so.*.max' => 'nullable|numeric',
            'nhan_xet' => 'nullable|string',
            'ket_luan' => 'nullable|string',
            'file_ket_qua.*' => 'nullable|file|max:10240', // 10MB
        ]);

        try {
            DB::beginTransaction();

            $data = $validated;
            // Tự động set trạng thái thành Có kết quả khi lưu kết quả
            $data['trang_thai'] = 'Có kết quả';

            // Xử lý upload file kết quả
            if ($request->hasFile('file_ket_qua')) {
                $filePaths = $xetNghiem->file_ket_qua ?? [];
                foreach ($request->file('file_ket_qua') as $file) {
                    $path = $file->store('xet-nghiem', 'public');
                    $filePaths[] = $path;
                }
                $data['file_ket_qua'] = $filePaths;
            }

            $xetNghiem->update($data);

            DB::commit();

            return redirect()->route('doctor.benhan.edit', $xetNghiem->benh_an_id)
                ->with('success', 'Đã cập nhật kết quả xét nghiệm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }
}

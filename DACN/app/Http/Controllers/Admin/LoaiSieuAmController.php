<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChuyenKhoa;
use App\Models\LoaiSieuAm;
use App\Models\Phong;
use Illuminate\Http\Request;

class LoaiSieuAmController extends Controller
{
    /**
     * Admin: Danh sách loại siêu âm (File mẹ: app/Http/Controllers/Admin/LoaiSieuAmController.php)
     */
    public function index(Request $request)
    {
        $items = LoaiSieuAm::with(['chuyenKhoas', 'phong'])
            ->orderByDesc('created_at')
            ->get();

        return view('admin.loai_sieu_am.index', compact('items'));
    }

    /**
     * Hiển thị form tạo loại siêu âm (File mẹ: app/Http/Controllers/Admin/LoaiSieuAmController.php)
     */
    public function create()
    {
        $chuyenKhoas = ChuyenKhoa::orderBy('ten')->get();
        $phongs = Phong::orderBy('ten')->get();

        return view('admin.loai_sieu_am.create', compact('chuyenKhoas', 'phongs'));
    }

    /**
     * Lưu loại siêu âm mới (File mẹ: app/Http/Controllers/Admin/LoaiSieuAmController.php)
     */
    public function store(Request $request)
    {
        $request->validate([
            'ten' => 'required|string|max:255|unique:loai_sieu_ams,ten',
            'mo_ta' => 'nullable|string',
            'gia_mac_dinh' => 'required|numeric|min:0',
            'thoi_gian_uoc_tinh' => 'required|integer|min:1',
            'phong_id' => 'nullable|integer|exists:phongs,id',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->only(['ten', 'mo_ta', 'gia_mac_dinh', 'thoi_gian_uoc_tinh', 'phong_id']);
        $data['is_active'] = (bool) ($request->input('is_active', true));

        $item = LoaiSieuAm::create($data);

        if ($request->has('chuyen_khoa_ids')) {
            $item->chuyenKhoas()->sync($request->input('chuyen_khoa_ids', []));
        }

        return redirect()->route('admin.loai-sieu-am.index')
            ->with('success', 'Thêm loại siêu âm thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa (File mẹ: app/Http/Controllers/Admin/LoaiSieuAmController.php)
     */
    public function edit(LoaiSieuAm $loaiSieuAm)
    {
        $chuyenKhoas = ChuyenKhoa::orderBy('ten')->get();
        $phongs = Phong::orderBy('ten')->get();
        $selected = $loaiSieuAm->chuyenKhoas()->pluck('chuyen_khoas.id')->toArray();

        return view('admin.loai_sieu_am.edit', compact('loaiSieuAm', 'chuyenKhoas', 'phongs', 'selected'));
    }

    /**
     * Cập nhật loại siêu âm (File mẹ: app/Http/Controllers/Admin/LoaiSieuAmController.php)
     */
    public function update(Request $request, LoaiSieuAm $loaiSieuAm)
    {
        $request->validate([
            'ten' => 'required|string|max:255|unique:loai_sieu_ams,ten,' . $loaiSieuAm->id,
            'mo_ta' => 'nullable|string',
            'gia_mac_dinh' => 'required|numeric|min:0',
            'thoi_gian_uoc_tinh' => 'required|integer|min:1',
            'phong_id' => 'nullable|integer|exists:phongs,id',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->only(['ten', 'mo_ta', 'gia_mac_dinh', 'thoi_gian_uoc_tinh', 'phong_id']);
        $data['is_active'] = (bool) ($request->input('is_active', false));

        $loaiSieuAm->update($data);

        if ($request->has('chuyen_khoa_ids')) {
            $loaiSieuAm->chuyenKhoas()->sync($request->input('chuyen_khoa_ids', []));
        }

        return redirect()->route('admin.loai-sieu-am.index')
            ->with('success', 'Cập nhật loại siêu âm thành công!');
    }

    /**
     * Xóa loại siêu âm (File mẹ: app/Http/Controllers/Admin/LoaiSieuAmController.php)
     */
    public function destroy(LoaiSieuAm $loaiSieuAm)
    {
        if ($loaiSieuAm->sieuAms()->count() > 0) {
            return back()->with('error', 'Không thể xóa loại siêu âm đã được sử dụng!');
        }

        $loaiSieuAm->delete();

        return redirect()->route('admin.loai-sieu-am.index')
            ->with('success', 'Xóa loại siêu âm thành công!');
    }
}

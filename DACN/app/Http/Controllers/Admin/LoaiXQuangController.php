<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChuyenKhoa;
use App\Models\LoaiXQuang;
use App\Models\Phong;
use Illuminate\Http\Request;

class LoaiXQuangController extends Controller
{
    public function index(Request $request)
    {
        $items = LoaiXQuang::with(['chuyenKhoas', 'phong'])
            ->orderByDesc('created_at')
            ->get();

        return view('admin.loai_x_quang.index', compact('items'));
    }

    public function create()
    {
        $chuyenKhoas = ChuyenKhoa::orderBy('ten')->get();
        $phongs = Phong::orderBy('ten')->get();

        return view('admin.loai_x_quang.create', compact('chuyenKhoas', 'phongs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten' => 'required|string|max:255',
            'ma' => 'nullable|string|max:100|unique:loai_x_quangs,ma',
            'mo_ta' => 'nullable|string',
            'gia' => 'required|numeric|min:0',
            'thoi_gian_uoc_tinh' => 'required|integer|min:1',
            'phong_id' => 'nullable|integer|exists:phongs,id',
            'active' => 'nullable|boolean',
        ]);

        $data = $request->only(['ten', 'ma', 'mo_ta', 'gia', 'thoi_gian_uoc_tinh', 'phong_id']);
        $data['active'] = (bool) ($request->input('active', true));

        $item = LoaiXQuang::create($data);

        if ($request->has('chuyen_khoa_ids')) {
            $item->chuyenKhoas()->sync($request->input('chuyen_khoa_ids', []));
        }

        return redirect()->route('admin.loai-x-quang.index')
            ->with('success', 'Thêm loại X-Quang thành công!');
    }

    public function edit(LoaiXQuang $loaiXQuang)
    {
        $chuyenKhoas = ChuyenKhoa::orderBy('ten')->get();
        $phongs = Phong::orderBy('ten')->get();
        $selected = $loaiXQuang->chuyenKhoas()->pluck('chuyen_khoas.id')->toArray();

        return view('admin.loai_x_quang.edit', compact('loaiXQuang', 'chuyenKhoas', 'phongs', 'selected'));
    }

    public function update(Request $request, LoaiXQuang $loaiXQuang)
    {
        $request->validate([
            'ten' => 'required|string|max:255',
            'ma' => 'nullable|string|max:100|unique:loai_x_quangs,ma,' . $loaiXQuang->id,
            'mo_ta' => 'nullable|string',
            'gia' => 'required|numeric|min:0',
            'thoi_gian_uoc_tinh' => 'required|integer|min:1',
            'phong_id' => 'nullable|integer|exists:phongs,id',
            'active' => 'nullable|boolean',
        ]);

        $data = $request->only(['ten', 'ma', 'mo_ta', 'gia', 'thoi_gian_uoc_tinh', 'phong_id']);
        $data['active'] = (bool) ($request->input('active', false));

        $loaiXQuang->update($data);

        if ($request->has('chuyen_khoa_ids')) {
            $loaiXQuang->chuyenKhoas()->sync($request->input('chuyen_khoa_ids', []));
        }

        return redirect()->route('admin.loai-x-quang.index')
            ->with('success', 'Cập nhật loại X-Quang thành công!');
    }

    public function destroy(LoaiXQuang $loaiXQuang)
    {
        if ($loaiXQuang->xQuangs()->count() > 0) {
            return back()->with('error', 'Không thể xóa loại X-Quang đã được sử dụng!');
        }

        $loaiXQuang->delete();

        return redirect()->route('admin.loai-x-quang.index')
            ->with('success', 'Xóa loại X-Quang thành công!');
    }
}

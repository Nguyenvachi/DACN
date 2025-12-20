<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChuyenKhoa;
use App\Models\LoaiNoiSoi;
use App\Models\Phong;
use Illuminate\Http\Request;

class LoaiNoiSoiController extends Controller
{
    public function index(Request $request)
    {
        $items = LoaiNoiSoi::with(['chuyenKhoas', 'phong'])
            ->orderByDesc('created_at')
            ->get();

        return view('admin.loai_noi_soi.index', compact('items'));
    }

    public function create()
    {
        $chuyenKhoas = ChuyenKhoa::orderBy('ten')->get();
        $phongs = Phong::orderBy('ten')->get();

        return view('admin.loai_noi_soi.create', compact('chuyenKhoas', 'phongs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten' => 'required|string|max:255',
            'ma' => 'nullable|string|max:100|unique:loai_noi_sois,ma',
            'mo_ta' => 'nullable|string',
            'gia' => 'required|numeric|min:0',
            'thoi_gian_uoc_tinh' => 'required|integer|min:1',
            'phong_id' => 'nullable|integer|exists:phongs,id',
            'active' => 'nullable|boolean',
        ]);

        $data = $request->only(['ten', 'ma', 'mo_ta', 'gia', 'thoi_gian_uoc_tinh', 'phong_id']);
        $data['active'] = (bool) ($request->input('active', true));

        $item = LoaiNoiSoi::create($data);

        if ($request->has('chuyen_khoa_ids')) {
            $item->chuyenKhoas()->sync($request->input('chuyen_khoa_ids', []));
        }

        return redirect()->route('admin.loai-noi-soi.index')
            ->with('success', 'Thêm loại Nội soi thành công!');
    }

    public function edit(LoaiNoiSoi $loaiNoiSoi)
    {
        $chuyenKhoas = ChuyenKhoa::orderBy('ten')->get();
        $phongs = Phong::orderBy('ten')->get();
        $selected = $loaiNoiSoi->chuyenKhoas()->pluck('chuyen_khoas.id')->toArray();

        return view('admin.loai_noi_soi.edit', compact('loaiNoiSoi', 'chuyenKhoas', 'phongs', 'selected'));
    }

    public function update(Request $request, LoaiNoiSoi $loaiNoiSoi)
    {
        $request->validate([
            'ten' => 'required|string|max:255',
            'ma' => 'nullable|string|max:100|unique:loai_noi_sois,ma,' . $loaiNoiSoi->id,
            'mo_ta' => 'nullable|string',
            'gia' => 'required|numeric|min:0',
            'thoi_gian_uoc_tinh' => 'required|integer|min:1',
            'phong_id' => 'nullable|integer|exists:phongs,id',
            'active' => 'nullable|boolean',
        ]);

        $data = $request->only(['ten', 'ma', 'mo_ta', 'gia', 'thoi_gian_uoc_tinh', 'phong_id']);
        $data['active'] = (bool) ($request->input('active', false));

        $loaiNoiSoi->update($data);

        if ($request->has('chuyen_khoa_ids')) {
            $loaiNoiSoi->chuyenKhoas()->sync($request->input('chuyen_khoa_ids', []));
        }

        return redirect()->route('admin.loai-noi-soi.index')
            ->with('success', 'Cập nhật loại Nội soi thành công!');
    }

    public function destroy(LoaiNoiSoi $loaiNoiSoi)
    {
        if ($loaiNoiSoi->noiSois()->count() > 0) {
            return back()->with('error', 'Không thể xóa loại Nội soi đã được sử dụng!');
        }

        $loaiNoiSoi->delete();

        return redirect()->route('admin.loai-noi-soi.index')
            ->with('success', 'Xóa loại Nội soi thành công!');
    }
}

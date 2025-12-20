<?php

namespace App\Http\Controllers;

use App\Models\ChuyenKhoa;
use App\Models\LoaiXetNghiem;
use App\Models\Phong;
use Illuminate\Http\Request;

class LoaiXetNghiemController extends Controller
{
    /**
     * Admin: Danh sách loại xét nghiệm
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $items = LoaiXetNghiem::with(['chuyenKhoas', 'phong'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where('ten', 'like', "%{$q}%")
                    ->orWhere('ma', 'like', "%{$q}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(25)
            ->appends(['q' => $q]);

        return view('admin.loai_xet_nghiem.index', compact('items', 'q'));
    }

    public function create()
    {
        $chuyenKhoas = ChuyenKhoa::orderBy('ten')->get();
        $phongs = Phong::orderBy('ten')->get();

        return view('admin.loai_xet_nghiem.create', compact('chuyenKhoas', 'phongs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten' => 'required|string|max:255',
            'ma' => 'nullable|string|max:100|unique:loai_xet_nghiems,ma',
            'mo_ta' => 'nullable|string',
            'gia' => 'required|numeric|min:0',
            'thoi_gian_uoc_tinh' => 'required|integer|min:1',
            'phong_id' => 'nullable|integer|exists:phongs,id',
            'active' => 'nullable|boolean',
        ]);

        $data = $request->only(['ten', 'ma', 'mo_ta', 'gia', 'thoi_gian_uoc_tinh', 'phong_id']);
        $data['active'] = (bool) ($request->input('active', true));

        $item = LoaiXetNghiem::create($data);

        if ($request->has('chuyen_khoa_ids')) {
            $item->chuyenKhoas()->sync($request->input('chuyen_khoa_ids', []));
        }

        return redirect()->route('admin.loai-xet-nghiem.index')
            ->with('success', 'Thêm loại xét nghiệm thành công!');
    }

    public function edit(LoaiXetNghiem $loaiXetNghiem)
    {
        $chuyenKhoas = ChuyenKhoa::orderBy('ten')->get();
        $phongs = Phong::orderBy('ten')->get();
        $selected = $loaiXetNghiem->chuyenKhoas()->pluck('chuyen_khoas.id')->toArray();

        return view('admin.loai_xet_nghiem.edit', compact('loaiXetNghiem', 'chuyenKhoas', 'phongs', 'selected'));
    }

    public function update(Request $request, LoaiXetNghiem $loaiXetNghiem)
    {
        $request->validate([
            'ten' => 'required|string|max:255',
            'ma' => 'nullable|string|max:100|unique:loai_xet_nghiems,ma,' . $loaiXetNghiem->id,
            'mo_ta' => 'nullable|string',
            'gia' => 'required|numeric|min:0',
            'thoi_gian_uoc_tinh' => 'required|integer|min:1',
            'phong_id' => 'nullable|integer|exists:phongs,id',
            'active' => 'nullable|boolean',
        ]);

        $data = $request->only(['ten', 'ma', 'mo_ta', 'gia', 'thoi_gian_uoc_tinh', 'phong_id']);
        $data['active'] = (bool) ($request->input('active', false));

        $loaiXetNghiem->update($data);

        if ($request->has('chuyen_khoa_ids')) {
            $loaiXetNghiem->chuyenKhoas()->sync($request->input('chuyen_khoa_ids', []));
        }

        return redirect()->route('admin.loai-xet-nghiem.index')
            ->with('success', 'Cập nhật loại xét nghiệm thành công!');
    }

    public function destroy(LoaiXetNghiem $loaiXetNghiem)
    {
        if ($loaiXetNghiem->xetNghiems()->count() > 0) {
            return back()->with('error', 'Không thể xóa loại xét nghiệm đã được sử dụng!');
        }

        $loaiXetNghiem->delete();

        return redirect()->route('admin.loai-xet-nghiem.index')
            ->with('success', 'Xóa loại xét nghiệm thành công!');
    }
}

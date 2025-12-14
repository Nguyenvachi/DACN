<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoaiPhong;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LoaiPhongController extends Controller
{
    public function index()
    {
        $loaiPhongs = LoaiPhong::withCount('phongs')->orderBy('ten')->paginate(20);
        return view('admin.loaiphong.index', compact('loaiPhongs'));
    }

    public function create()
    {
        $loaiPhong = new LoaiPhong();
        return view('admin.loaiphong.form', compact('loaiPhong'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ten' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'mo_ta' => 'nullable|string',
        ]);

        // Auto generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['ten']);
        }

        LoaiPhong::create($data);
        return redirect()->route('admin.loaiphong.index')->with('success', 'Đã tạo loại phòng');
    }

    public function edit(LoaiPhong $loaiphong)
    {
        return view('admin.loaiphong.form', ['loaiPhong' => $loaiphong]);
    }

    public function update(Request $request, LoaiPhong $loaiphong)
    {
        $data = $request->validate([
            'ten' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'mo_ta' => 'nullable|string',
        ]);

        // Auto generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['ten']);
        }

        $loaiphong->update($data);
        return redirect()->route('admin.loaiphong.index')->with('success', 'Đã cập nhật loại phòng');
    }

    public function destroy(LoaiPhong $loaiphong)
    {
        if ($loaiphong->phongs()->count() > 0) {
            return redirect()->route('admin.loaiphong.index')->with('error', 'Không thể xóa loại phòng đang được sử dụng');
        }

        $loaiphong->delete();
        return redirect()->route('admin.loaiphong.index')->with('success', 'Đã xóa loại phòng');
    }
}

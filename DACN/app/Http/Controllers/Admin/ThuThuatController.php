<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoaiThuThuat;
use Illuminate\Http\Request;

class ThuThuatController extends Controller
{
    public function index()
    {
        $items = LoaiThuThuat::orderBy('ten')->paginate(20);
        return view('admin.thu-thuat.index', compact('items'));
    }

    public function create()
    {
        return view('admin.thu-thuat.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ten' => 'required|string|max:255',
            'gia_tien' => 'required|numeric|min:0',
            'thoi_gian' => 'required|integer|min:1',
            'mo_ta' => 'nullable|string',
            'hoat_dong' => 'boolean',
        ]);

        $data['hoat_dong'] = $request->has('hoat_dong');

        LoaiThuThuat::create($data);

        return redirect()->route('admin.thu-thuat.index')
            ->with('success', 'Thêm loại thủ thuật thành công!');
    }

    public function show(LoaiThuThuat $thuThuat)
    {
        return view('admin.thu-thuat.show', compact('thuThuat'));
    }

    public function edit(LoaiThuThuat $thuThuat)
    {
        return view('admin.thu-thuat.edit', compact('thuThuat'));
    }

    public function update(Request $request, LoaiThuThuat $thuThuat)
    {
        $data = $request->validate([
            'ten' => 'required|string|max:255',
            'gia_tien' => 'required|numeric|min:0',
            'thoi_gian' => 'required|integer|min:1',
            'mo_ta' => 'nullable|string',
            'hoat_dong' => 'boolean',
        ]);

        $data['hoat_dong'] = $request->has('hoat_dong');

        $thuThuat->update($data);

        return redirect()->route('admin.thu-thuat.index')
            ->with('success', 'Cập nhật loại thủ thuật thành công!');
    }

    public function destroy(LoaiThuThuat $thuThuat)
    {
        $thuThuat->delete();

        return redirect()->route('admin.thu-thuat.index')
            ->with('success', 'Xóa loại thủ thuật thành công!');
    }
}

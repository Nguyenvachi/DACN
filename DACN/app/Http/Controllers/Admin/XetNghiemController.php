<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DanhMucXetNghiem;
use Illuminate\Http\Request;

class XetNghiemController extends Controller
{
    public function index()
    {
        $items = DanhMucXetNghiem::orderByDesc('id')->paginate(20);
        return view('admin.xet-nghiem.index', compact('items'));
    }

    public function create()
    {
        return view('admin.xet-nghiem.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ten_xet_nghiem' => 'required|string|max:255',
            'gia_tien' => 'required|numeric|min:0',
            'mo_ta' => 'nullable|string',
        ]);

        DanhMucXetNghiem::create([
            'ten_xet_nghiem' => $data['ten_xet_nghiem'],
            'gia_tien' => $data['gia_tien'],
            'ghi_chu' => $data['mo_ta'] ?? null,
        ]);

        return redirect()->route('admin.xet-nghiem.index')
            ->with('success', 'Thêm xét nghiệm thành công!');
    }

    public function show(DanhMucXetNghiem $xetNghiem)
    {
        return view('admin.xet-nghiem.show', compact('xetNghiem'));
    }

    public function edit(DanhMucXetNghiem $xetNghiem)
    {
        return view('admin.xet-nghiem.edit', compact('xetNghiem'));
    }

    public function update(Request $request, DanhMucXetNghiem $xetNghiem)
    {
        $data = $request->validate([
            'ten_xet_nghiem' => 'required|string|max:255',
            'gia_tien' => 'required|numeric|min:0',
            'mo_ta' => 'nullable|string',
        ]);

        $xetNghiem->update([
            'ten_xet_nghiem' => $data['ten_xet_nghiem'],
            'gia_tien' => $data['gia_tien'],
            'ghi_chu' => $data['mo_ta'] ?? $xetNghiem->ghi_chu,
        ]);

        return redirect()->route('admin.xet-nghiem.index')
            ->with('success', 'Cập nhật xét nghiệm thành công!');
    }

    public function destroy(DanhMucXetNghiem $xetNghiem)
    {
        $xetNghiem->delete();

        return redirect()->route('admin.xet-nghiem.index')
            ->with('success', 'Xóa xét nghiệm thành công!');
    }
}

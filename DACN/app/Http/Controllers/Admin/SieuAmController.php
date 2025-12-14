<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoaiSieuAm;
use Illuminate\Http\Request;

class SieuAmController extends Controller
{
    public function index()
    {
        $items = LoaiSieuAm::orderBy('ten')->paginate(20);
        return view('admin.sieu-am.index', compact('items'));
    }

    public function create()
    {
        return view('admin.sieu-am.create');
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

        LoaiSieuAm::create($data);

        return redirect()->route('admin.sieu-am.index')
            ->with('success', 'Thêm loại siêu âm thành công!');
    }

    public function show($id)
    {
        $loaiSieuAm = LoaiSieuAm::findOrFail($id);
        return view('admin.sieu-am.show', compact('loaiSieuAm'));
    }

    public function edit($id)
    {
        $loaiSieuAm = LoaiSieuAm::findOrFail($id);
        return view('admin.sieu-am.edit', compact('loaiSieuAm'));
    }

    public function update(Request $request, $id)
    {
        $loaiSieuAm = LoaiSieuAm::findOrFail($id);

        $data = $request->validate([
            'ten' => 'required|string|max:255',
            'gia_tien' => 'required|numeric|min:0',
            'thoi_gian' => 'required|integer|min:1',
            'mo_ta' => 'nullable|string',
            'hoat_dong' => 'boolean',
        ]);

        $data['hoat_dong'] = $request->has('hoat_dong');

        $loaiSieuAm->update($data);

        return redirect()->route('admin.sieu-am.index')
            ->with('success', 'Cập nhật loại siêu âm thành công!');
    }

    public function destroy($id)
    {
        $loaiSieuAm = LoaiSieuAm::findOrFail($id);
        $loaiSieuAm->delete();

        return redirect()->route('admin.sieu-am.index')
            ->with('success', 'Xóa loại siêu âm thành công!');
    }
}

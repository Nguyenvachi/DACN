<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThuThuat;
use Illuminate\Http\Request;

class ThuThuatController extends Controller
{
    public function index()
    {
        $items = ThuThuat::with(['benhAn.user', 'bacSi'])->orderByDesc('id')->paginate(20);
        return view('admin.thu-thuat.index', compact('items'));
    }

    public function create()
    {
        return view('admin.thu-thuat.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ten_thu_thuat' => 'required|string|max:255',
            'gia_tien' => 'required|numeric|min:0',
            'mo_ta' => 'nullable|string',
        ]);

        ThuThuat::create($data);

        return redirect()->route('admin.thu-thuat.index')
            ->with('success', 'Thêm thủ thuật thành công!');
    }

    public function show(ThuThuat $thuThuat)
    {
        $thuThuat->load(['benhAn.user', 'bacSi']);
        return view('admin.thu-thuat.show', compact('thuThuat'));
    }

    public function edit(ThuThuat $thuThuat)
    {
        return view('admin.thu-thuat.edit', compact('thuThuat'));
    }

    public function update(Request $request, ThuThuat $thuThuat)
    {
        $data = $request->validate([
            'ten_thu_thuat' => 'required|string|max:255',
            'gia_tien' => 'required|numeric|min:0',
            'mo_ta' => 'nullable|string',
        ]);

        $thuThuat->update($data);

        return redirect()->route('admin.thu-thuat.index')
            ->with('success', 'Cập nhật thủ thuật thành công!');
    }

    public function destroy(ThuThuat $thuThuat)
    {
        $thuThuat->delete();

        return redirect()->route('admin.thu-thuat.index')
            ->with('success', 'Xóa thủ thuật thành công!');
    }
}

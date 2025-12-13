<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SieuAm;
use Illuminate\Http\Request;

class SieuAmController extends Controller
{
    public function index()
    {
        $items = SieuAm::with(['benhAn.user', 'bacSi'])->orderByDesc('id')->paginate(20);
        return view('admin.sieu-am.index', compact('items'));
    }

    public function create()
    {
        return view('admin.sieu-am.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'loai_sieu_am' => 'required|string|max:255',
            'gia_tien' => 'required|numeric|min:0',
            'mo_ta' => 'nullable|string',
        ]);

        SieuAm::create($data);

        return redirect()->route('admin.sieu-am.index')
            ->with('success', 'Thêm siêu âm thành công!');
    }

    public function show(SieuAm $sieuAm)
    {
        $sieuAm->load(['benhAn.user', 'bacSi']);
        return view('admin.sieu-am.show', compact('sieuAm'));
    }

    public function edit(SieuAm $sieuAm)
    {
        return view('admin.sieu-am.edit', compact('sieuAm'));
    }

    public function update(Request $request, SieuAm $sieuAm)
    {
        $data = $request->validate([
            'loai_sieu_am' => 'required|string|max:255',
            'gia_tien' => 'required|numeric|min:0',
            'mo_ta' => 'nullable|string',
        ]);

        $sieuAm->update($data);

        return redirect()->route('admin.sieu-am.index')
            ->with('success', 'Cập nhật siêu âm thành công!');
    }

    public function destroy(SieuAm $sieuAm)
    {
        $sieuAm->delete();

        return redirect()->route('admin.sieu-am.index')
            ->with('success', 'Xóa siêu âm thành công!');
    }
}

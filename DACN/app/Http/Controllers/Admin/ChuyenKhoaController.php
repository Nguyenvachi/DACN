<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChuyenKhoa;
use App\Models\BacSi;
use Illuminate\Http\Request;

class ChuyenKhoaController extends Controller
{
    public function index()
    {
        $khoas = ChuyenKhoa::withCount('bacSis')->orderBy('ten')->paginate(20);
        return view('admin.chuyenkhoa.index', compact('khoas'));
    }

    public function create()
    {
        $khoa = new ChuyenKhoa();
        $bacSis = BacSi::orderBy('ho_ten')->get();
        return view('admin.chuyenkhoa.form', compact('khoa','bacSis'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ten' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'mo_ta' => 'nullable|string',
            'bac_si_ids' => 'array',
            'bac_si_ids.*' => 'integer|exists:bac_sis,id',
        ]);
        $khoa = ChuyenKhoa::create($data);
        if (!empty($data['bac_si_ids'])) {
            $khoa->bacSis()->sync($data['bac_si_ids']);
        }
        return redirect()->route('admin.chuyenkhoa.index')->with('success','Đã tạo chuyên khoa');
    }

    public function edit(ChuyenKhoa $chuyenkhoa)
    {
        $bacSis = BacSi::orderBy('ho_ten')->get();
        return view('admin.chuyenkhoa.form', ['khoa' => $chuyenkhoa, 'bacSis' => $bacSis]);
    }

    public function update(Request $request, ChuyenKhoa $chuyenkhoa)
    {
        $data = $request->validate([
            'ten' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'mo_ta' => 'nullable|string',
            'bac_si_ids' => 'array',
            'bac_si_ids.*' => 'integer|exists:bac_sis,id',
        ]);
        $chuyenkhoa->update($data);
        $chuyenkhoa->bacSis()->sync($data['bac_si_ids'] ?? []);
        return redirect()->route('admin.chuyenkhoa.index')->with('success','Đã cập nhật chuyên khoa');
    }

    public function destroy(ChuyenKhoa $chuyenkhoa)
    {
        $chuyenkhoa->delete();
        return redirect()->route('admin.chuyenkhoa.index')->with('success','Đã xóa chuyên khoa');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Thuoc;
use Illuminate\Http\Request;

class ThuocController extends Controller
{
    public function index()
    {
        $thuocs = Thuoc::withSum('kho as ton_kho', 'so_luong')
            ->orderBy('ten')
            ->paginate(20);
        return view('admin.thuoc.index', compact('thuocs'));
    }

    public function create()
    {
        $thuoc = new Thuoc();
        return view('admin.thuoc.form', compact('thuoc'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ten' => 'required|string|max:255',
            'hoat_chat' => 'nullable|string|max:255',
            'ham_luong' => 'nullable|string|max:255',
            'don_vi' => 'nullable|string|max:50',
            'gia_tham_khao' => 'nullable|numeric|min:0',
        ]);
        Thuoc::create($data);
        return redirect()->route('admin.thuoc.index')->with('success','Đã thêm thuốc');
    }

    public function edit(Thuoc $thuoc)
    {
        return view('admin.thuoc.form', compact('thuoc'));
    }

    public function update(Request $request, Thuoc $thuoc)
    {
        $data = $request->validate([
            'ten' => 'required|string|max:255',
            'hoat_chat' => 'nullable|string|max:255',
            'ham_luong' => 'nullable|string|max:255',
            'don_vi' => 'nullable|string|max:50',
            'gia_tham_khao' => 'nullable|numeric|min:0',
        ]);
        $thuoc->update($data);
        return redirect()->route('admin.thuoc.index')->with('success','Đã cập nhật thuốc');
    }

    public function destroy(Thuoc $thuoc)
    {
        // Kiểm tra có trong phiếu nhập không
        if ($thuoc->phieuNhapItems()->exists()) {
            return back()->with('error', 'Không thể xóa thuốc này vì đã có trong phiếu nhập. Vui lòng xóa các phiếu nhập liên quan trước.');
        }

        // Kiểm tra có trong phiếu xuất không
        if ($thuoc->phieuXuatItems()->exists()) {
            return back()->with('error', 'Không thể xóa thuốc này vì đã có trong phiếu xuất. Vui lòng xóa các phiếu xuất liên quan trước.');
        }

        // Kiểm tra có tồn kho không
        if ($thuoc->kho()->exists()) {
            return back()->with('error', 'Không thể xóa thuốc này vì còn tồn kho. Vui lòng xuất hết tồn kho trước.');
        }

        // Xóa liên kết với NCC (nếu có)
        $thuoc->nhaCungCaps()->detach();

        $thuoc->delete();
        return redirect()->route('admin.thuoc.index')->with('success', 'Đã xóa thuốc');
    }
}

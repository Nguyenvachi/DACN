<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NhaCungCap;
use App\Models\Thuoc;

class NhaCungCapController extends Controller
{
    public function index()
    {
        $nccs = NhaCungCap::withCount('thuocs')->orderBy('ten')->paginate(20);
        return view('admin.ncc.index', compact('nccs'));
    }

    public function create()
    {
        $ncc = new NhaCungCap();
        return view('admin.ncc.form', compact('ncc'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ten' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'so_dien_thoai' => 'nullable|string|max:50',
            'dia_chi' => 'nullable|string|max:255',
            'ghi_chu' => 'nullable|string',
        ]);
        NhaCungCap::create($data);
        return redirect()->route('admin.ncc.index')->with('success','Đã thêm nhà cung cấp');
    }

    public function edit(NhaCungCap $ncc)
    {
        return view('admin.ncc.form', compact('ncc'));
    }

    public function update(Request $request, NhaCungCap $ncc)
    {
        $data = $request->validate([
            'ten' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'so_dien_thoai' => 'nullable|string|max:50',
            'dia_chi' => 'nullable|string|max:255',
            'ghi_chu' => 'nullable|string',
        ]);
        $ncc->update($data);
        return redirect()->route('admin.ncc.index')->with('success','Đã cập nhật nhà cung cấp');
    }

    public function destroy(NhaCungCap $ncc)
    {
        // Kiểm tra có phiếu nhập nào không
        if ($ncc->phieuNhaps()->exists()) {
            return back()->with('error', 'Không thể xóa nhà cung cấp này vì đã có phiếu nhập. Vui lòng xóa các phiếu nhập trước.');
        }

        // Kiểm tra có thuốc liên kết không (tùy chọn - có thể cho phép xóa vì có cascade)
        $soThuoc = $ncc->thuocs()->count();
        if ($soThuoc > 0) {
            // Xóa tất cả liên kết thuốc trước (cascade sẽ tự động xử lý)
            $ncc->thuocs()->detach();
        }

        $ncc->delete();
        return redirect()->route('admin.ncc.index')->with('success', 'Đã xóa nhà cung cấp');
    }

    /**
     * Quản lý thuốc của NCC
     */
    public function thuocs(NhaCungCap $ncc)
    {
        $thuocsCuaNcc = $ncc->thuocs()->orderBy('ten')->get();
        $allThuocs = Thuoc::orderBy('ten')->get();

        return view('admin.ncc.thuocs', compact('ncc', 'thuocsCuaNcc', 'allThuocs'));
    }

    /**
     * Thêm thuốc vào NCC
     */
    public function addThuoc(Request $request, NhaCungCap $ncc)
    {
        $validated = $request->validate([
            'thuoc_id' => 'required|exists:thuocs,id',
            'gia_nhap_mac_dinh' => 'nullable|numeric|min:0',
        ]);

        // Kiểm tra đã tồn tại chưa
        if ($ncc->cungCapThuoc($validated['thuoc_id'])) {
            return back()->with('error', 'Thuốc này đã có trong danh sách NCC');
        }

        $ncc->thuocs()->attach($validated['thuoc_id'], [
            'gia_nhap_mac_dinh' => $validated['gia_nhap_mac_dinh'] ?? null,
        ]);

        return back()->with('success', 'Đã thêm thuốc vào nhà cung cấp');
    }

    /**
     * Cập nhật giá nhập mặc định
     */
    public function updateGiaThuoc(Request $request, NhaCungCap $ncc, Thuoc $thuoc)
    {
        $validated = $request->validate([
            'gia_nhap_mac_dinh' => 'required|numeric|min:0',
        ]);

        $ncc->thuocs()->updateExistingPivot($thuoc->id, [
            'gia_nhap_mac_dinh' => $validated['gia_nhap_mac_dinh'],
        ]);

        return back()->with('success', 'Đã cập nhật giá nhập mặc định');
    }

    /**
     * Xóa thuốc khỏi NCC
     */
    public function removeThuoc(NhaCungCap $ncc, Thuoc $thuoc)
    {
        $ncc->thuocs()->detach($thuoc->id);
        return back()->with('success', 'Đã xóa thuốc khỏi nhà cung cấp');
    }
}

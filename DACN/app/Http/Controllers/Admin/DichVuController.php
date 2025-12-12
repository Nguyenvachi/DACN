<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DichVu;
use App\Models\ChuyenKhoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DichVuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DichVu::with('chuyenKhoa');

        // Filter by type
        if ($request->filled('loai')) {
            $query->where('loai', $request->loai);
        }

        // Filter by specialty
        if ($request->filled('chuyen_khoa_id')) {
            $query->where('chuyen_khoa_id', $request->chuyen_khoa_id);
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('ten_dich_vu', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->filled('hoat_dong')) {
            $query->where('hoat_dong', $request->hoat_dong === '1');
        }

        $dichVus = $query->orderBy('loai')->orderBy('ten_dich_vu')->paginate(20);
        $chuyenKhoas = ChuyenKhoa::all();

        return view('admin.dich-vu.index', compact('dichVus', 'chuyenKhoas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $chuyenKhoas = ChuyenKhoa::all();
        return view('admin.dich-vu.create', compact('chuyenKhoas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten_dich_vu' => 'required|string|max:255',
            'loai' => 'required|in:Cơ bản,Nâng cao',
            'chuyen_khoa_id' => 'nullable|exists:chuyen_khoas,id',
            'gia_tien' => 'required|numeric|min:0',
            'thoi_gian' => 'nullable|integer|min:1',
            'mo_ta' => 'nullable|string',
            'hoat_dong' => 'boolean',
        ]);

        try {
            DichVu::create($validated);

            return redirect()->route('admin.dich-vu.index')
                ->with('success', 'Đã thêm dịch vụ thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(DichVu $dichVu)
    {
        $dichVu->load('chuyenKhoa');
        return view('admin.dich-vu.show', compact('dichVu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DichVu $dichVu)
    {
        $chuyenKhoas = ChuyenKhoa::all();
        return view('admin.dich-vu.edit', compact('dichVu', 'chuyenKhoas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DichVu $dichVu)
    {
        $validated = $request->validate([
            'ten_dich_vu' => 'required|string|max:255',
            'loai' => 'required|in:Cơ bản,Nâng cao',
            'chuyen_khoa_id' => 'nullable|exists:chuyen_khoas,id',
            'gia_tien' => 'required|numeric|min:0',
            'thoi_gian' => 'nullable|integer|min:1',
            'mo_ta' => 'nullable|string',
            'hoat_dong' => 'boolean',
        ]);

        try {
            $dichVu->update($validated);

            return redirect()->route('admin.dich-vu.index')
                ->with('success', 'Đã cập nhật dịch vụ thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DichVu $dichVu)
    {
        try {
            // Check if service is being used
            $isUsed = DB::table('lich_hens')
                ->where('dich_vu_id', $dichVu->id)
                ->exists();

            if ($isUsed) {
                return back()->with('error', 'Không thể xóa dịch vụ đang được sử dụng!');
            }

            $dichVu->delete();

            return redirect()->route('admin.dich-vu.index')
                ->with('success', 'Đã xóa dịch vụ thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Toggle service status
     */
    public function toggleStatus(DichVu $dichVu)
    {
        try {
            $dichVu->update(['hoat_dong' => !$dichVu->hoat_dong]);

            return back()->with('success', 'Đã cập nhật trạng thái dịch vụ!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}

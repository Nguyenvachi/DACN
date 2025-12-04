<?php

namespace App\Http\Controllers;

use App\Models\DichVu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DichVuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Lấy tất cả dịch vụ từ database
        $danhSachDichVu = DichVu::paginate(15); // THAY ĐỔI: Thêm phân trang

        //Trả về view và truyền danh sách dịch vụ ra view
        return view('admin.dichvu.index', ['dsDichVu' => $danhSachDichVu]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Chỉ đơn giản là trả về view chứa form
        return view('admin.dichvu.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //1. Validate dữ liệu (bắt buộc nhập, định dạng...) 
        $request->validate([
            'ten_dich_vu' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'gia' => 'required|numeric|min:0',
            'thoi_gian_uoc_tinh' => 'required|integer|min:1',
        ]);

        //2. Lấy tất cả dữ liệu từ form và tạo dịch vụ mới
        DichVu::create($request->all());

        //3. Chuyển hướng về trang danh sách
        return redirect()->route('admin.dich-vu.index')
            ->with('success', 'Thêm dịch vụ mới thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(DichVu $dichVu)
    {
        return view('admin.dichvu.show', compact('dichVu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DichVu $dichVu)
    {
        return view('admin.dichvu.edit', ['dichVu' => $dichVu]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DichVu $dichVu)
    {
        // Validate dữ liệu
        $request->validate([
            'ten_dich_vu' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'gia' => 'required|numeric|min:0',
            'thoi_gian_uoc_tinh' => 'required|integer|min:1',
        ]);

        // Cập nhật
        $dichVu->update($request->all());

        return redirect()->route('admin.dich-vu.index')
            ->with('success', 'Cập nhật dịch vụ thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DichVu $dichVu)
    {
        // Kiểm tra xem có lịch hẹn nào sử dụng dịch vụ này không
        if ($dichVu->lichHens()->count() > 0) {
            return back()->with('error', 'Không thể xóa dịch vụ đã có lịch hẹn!');
        }

        $dichVu->delete();

        return redirect()->route('admin.dich-vu.index')
            ->with('success', 'Xóa dịch vụ thành công!');
    }

    /**
     * THÊM MỚI: Hàm hiển thị danh sách cho bệnh nhân
     */
    public function publicIndex()
    {
        // Đổi tên biến để khớp với view
        $dsDichVu = DichVu::all();

        return view('public.dichvu.index', compact('dsDichVu'));
    }
}

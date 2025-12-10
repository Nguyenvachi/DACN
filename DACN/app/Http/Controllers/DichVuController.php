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
    public function index(Request $request)
    {
        //Lấy tất cả dịch vụ từ database với filter
        $query = DichVu::query();

        // Lọc theo loại nếu có
        if ($request->filled('loai')) {
            $query->where('loai', $request->loai);
        }

        // Lọc theo trạng thái hoạt động
        if ($request->filled('hoat_dong')) {
            $query->where('hoat_dong', $request->hoat_dong == 'true');
        }

        // Tìm kiếm theo tên
        if ($request->filled('search')) {
            $query->where('ten_dich_vu', 'like', '%' . $request->search . '%');
        }

        $danhSachDichVu = $query->orderBy('loai')->orderBy('ten_dich_vu')->paginate(15);

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
            'loai' => 'required|in:Cơ bản,Nâng cao',
            'mo_ta' => 'nullable|string',
            'gia' => 'required|numeric|min:0',
            'thoi_gian_uoc_tinh' => 'required|integer|min:1',
            'hoat_dong' => 'nullable|boolean',
        ]);

        //2. Lấy dữ liệu và set mặc định
        $data = $request->all();
        $data['hoat_dong'] = $request->has('hoat_dong') ? true : false;

        //3. Tạo dịch vụ mới
        DichVu::create($data);

        //4. Chuyển hướng về trang danh sách
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
            'loai' => 'required|in:Cơ bản,Nâng cao',
            'mo_ta' => 'nullable|string',
            'gia' => 'required|numeric|min:0',
            'thoi_gian_uoc_tinh' => 'required|integer|min:1',
            'hoat_dong' => 'nullable|boolean',
        ]);

        // Cập nhật
        $data = $request->all();
        $data['hoat_dong'] = $request->has('hoat_dong') ? true : false;
        $dichVu->update($data);

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
     * THÊM MỚI: Hàm hiển thị danh sách cho bệnh nhân - CHỈ DỊCH VỤ CƠ BẢN
     */
    public function publicIndex()
    {
        // Chỉ lấy dịch vụ cơ bản và đang hoạt động
        $dsDichVu = DichVu::where('loai', 'Cơ bản')
            ->where('hoat_dong', true)
            ->orderBy('ten_dich_vu')
            ->get();

        return view('public.dichvu.index', compact('dsDichVu'));
    }
}

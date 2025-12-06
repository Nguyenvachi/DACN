<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DanhGia;
use App\Models\BacSi;
use Illuminate\Http\Request;

class DanhGiaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DanhGia::with(['user', 'bacSi', 'lichHen']);

        // Lọc theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Lọc theo bác sĩ
        if ($request->filled('bac_si_id')) {
            $query->where('bac_si_id', $request->bac_si_id);
        }

        // Lọc theo rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Tìm kiếm
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('noi_dung', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('bacSi', function($q) use ($search) {
                      $q->where('ho_ten', 'like', "%{$search}%");
                  });
            });
        }

        // Lấy tất cả dữ liệu cho DataTables (không dùng pagination của Laravel)
        $danhGias = $query->orderBy('created_at', 'desc')->get();
        $bacSis = BacSi::orderBy('ho_ten')->get();

        return view('admin.danhgia.index', compact('danhGias', 'bacSis'));
    }

    /**
     * Display the specified resource.
     */
    public function show(DanhGia $danhGia)
    {
        $danhGia->load(['user', 'bacSi', 'lichHen.dichVu']);
        return view('admin.danhgia.show', compact('danhGia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DanhGia $danhGia)
    {
        $request->validate([
            'trang_thai' => 'required|in:pending,approved,rejected',
        ]);

        $danhGia->update([
            'trang_thai' => $request->trang_thai,
        ]);

        return redirect()->route('admin.danhgia.index')
            ->with('success', 'Cập nhật trạng thái đánh giá thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DanhGia $danhGia)
    {
        $danhGia->delete();

        return redirect()->route('admin.danhgia.index')
            ->with('success', 'Xóa đánh giá thành công!');
    }

    /**
     * Approve a review
     */
    public function approve(DanhGia $danhGia)
    {
        $danhGia->update(['trang_thai' => 'approved']);

        return redirect()->back()
            ->with('success', 'Đã duyệt đánh giá thành công!');
    }

    /**
     * Reject a review
     */
    public function reject(DanhGia $danhGia)
    {
        $danhGia->update(['trang_thai' => 'rejected']);

        return redirect()->back()
            ->with('success', 'Đã từ chối đánh giá!');
    }
}

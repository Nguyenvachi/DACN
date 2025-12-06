<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\DanhGia;
use App\Models\LichHen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DanhGiaController extends Controller
{
    /**
     * Display a listing of the user's reviews.
     */
    public function index()
    {
        $danhGias = DanhGia::where('user_id', Auth::id())
            ->with(['bacSi', 'lichHen'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('patient.danhgia.index', compact('danhGias'));
    }

    /**
     * Show the form for creating a new review.
     */
    public function create(Request $request)
    {
        $lichHenId = $request->lich_hen_id;

        if (!$lichHenId) {
            return redirect()->back()->with('error', 'Không tìm thấy lịch hẹn!');
        }

        $lichHen = LichHen::with(['bacSi', 'dichVu'])
            ->where('id', $lichHenId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$lichHen) {
            return redirect()->back()->with('error', 'Không tìm thấy lịch hẹn!');
        }

        // Kiểm tra đã đánh giá chưa
        $existingReview = DanhGia::where('user_id', Auth::id())
            ->where('lich_hen_id', $lichHenId)
            ->first();

        if ($existingReview) {
            return redirect()->route('patient.danhgia.edit', $existingReview->id)
                ->with('info', 'Bạn đã đánh giá lịch hẹn này. Bạn có thể chỉnh sửa đánh giá.');
        }

        // Kiểm tra lịch hẹn đã hoàn thành chưa
        if ($lichHen->trang_thai !== 'Đã hoàn thành' && $lichHen->trang_thai !== 'Hoàn thành') {
            return redirect()->back()->with('error', 'Chỉ có thể đánh giá sau khi hoàn thành lịch hẹn!');
        }

        return view('patient.danhgia.create', compact('lichHen'));
    }

    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'lich_hen_id' => 'required|exists:lich_hens,id',
            'rating' => 'required|integer|min:1|max:5',
            'noi_dung' => 'required|string|min:10|max:1000',
        ], [
            'rating.required' => 'Vui lòng chọn số sao đánh giá!',
            'rating.min' => 'Đánh giá tối thiểu 1 sao!',
            'rating.max' => 'Đánh giá tối đa 5 sao!',
            'noi_dung.required' => 'Vui lòng nhập nội dung đánh giá!',
            'noi_dung.min' => 'Nội dung đánh giá phải có ít nhất 10 ký tự!',
            'noi_dung.max' => 'Nội dung đánh giá không được quá 1000 ký tự!',
        ]);

        // Kiểm tra lịch hẹn thuộc về user
        $lichHen = LichHen::where('id', $request->lich_hen_id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$lichHen) {
            return redirect()->back()->with('error', 'Không tìm thấy lịch hẹn!');
        }

        // Kiểm tra đã đánh giá chưa
        $existingReview = DanhGia::where('user_id', Auth::id())
            ->where('lich_hen_id', $request->lich_hen_id)
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'Bạn đã đánh giá lịch hẹn này rồi!');
        }

        DanhGia::create([
            'user_id' => Auth::id(),
            'bac_si_id' => $lichHen->bac_si_id,
            'lich_hen_id' => $request->lich_hen_id,
            'rating' => $request->rating,
            'noi_dung' => $request->noi_dung,
            'trang_thai' => 'approved', // Auto approve by default
        ]);

        return redirect()->route('lichhen.my')
            ->with('success', 'Cảm ơn bạn đã đánh giá! Đánh giá của bạn rất quan trọng với chúng tôi.');
    }

    /**
     * Show the form for editing the specified review.
     */
    public function edit(DanhGia $danhGia)
    {
        // Check ownership
        if ($danhGia->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền chỉnh sửa đánh giá này!');
        }

        $danhGia->load(['bacSi', 'lichHen']);

        return view('patient.danhgia.edit', compact('danhGia'));
    }

    /**
     * Update the specified review in storage.
     */
    public function update(Request $request, DanhGia $danhGia)
    {
        // Check ownership
        if ($danhGia->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền chỉnh sửa đánh giá này!');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'noi_dung' => 'required|string|min:10|max:1000',
        ], [
            'rating.required' => 'Vui lòng chọn số sao đánh giá!',
            'rating.min' => 'Đánh giá tối thiểu 1 sao!',
            'rating.max' => 'Đánh giá tối đa 5 sao!',
            'noi_dung.required' => 'Vui lòng nhập nội dung đánh giá!',
            'noi_dung.min' => 'Nội dung đánh giá phải có ít nhất 10 ký tự!',
            'noi_dung.max' => 'Nội dung đánh giá không được quá 1000 ký tự!',
        ]);

        $danhGia->update([
            'rating' => $request->rating,
            'noi_dung' => $request->noi_dung,
        ]);

        return redirect()->route('patient.danhgia.index')
            ->with('success', 'Cập nhật đánh giá thành công!');
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy(DanhGia $danhGia)
    {
        // Check ownership
        if ($danhGia->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xóa đánh giá này!');
        }

        $danhGia->delete();

        return redirect()->route('patient.danhgia.index')
            ->with('success', 'Xóa đánh giá thành công!');
    }
}

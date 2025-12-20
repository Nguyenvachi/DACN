<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\SieuAm;
use App\Services\MedicalWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Controller quản lý Siêu âm của Nhân viên/Kỹ thuật viên
 * File mẹ: routes/web.php
 */
class SieuAmController extends Controller
{
    protected $workflowService;

    public function __construct(MedicalWorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    /**
     * Danh sách siêu âm chờ thực hiện
     */
    public function pending(Request $request)
    {
        $query = SieuAm::with(['benhAn.user', 'bacSi', 'loaiSieuAm'])
            ->whereIn('trang_thai', [SieuAm::STATUS_PENDING, SieuAm::STATUS_PROCESSING]);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('benhAn.user', function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%');
            });
        }

        $sieuAms = $query->orderBy('created_at', 'asc')->paginate(20);

        // THÊM: thống kê giống module Xét nghiệm (luồng riêng Siêu âm)
        $stats = [
            'pending' => SieuAm::pending()->count(),
            'processing' => SieuAm::processing()->count(),
            'completed_today' => SieuAm::completed()->whereDate('updated_at', today())->count(),
        ];

        return view('staff.sieuam.pending', compact('sieuAms', 'stats'));
    }

    /**
     * Danh sách siêu âm đã hoàn thành
     */
    public function completed(Request $request)
    {
        $query = SieuAm::with(['benhAn.user', 'bacSi', 'loaiSieuAm'])
            ->completed();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('benhAn.user', function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%');
            });
        }

        $sieuAms = $query->orderBy('updated_at', 'desc')->paginate(20);

        // THÊM: thống kê giống module Xét nghiệm
        $stats = [
            'today' => SieuAm::completed()->whereDate('updated_at', today())->count(),
            'this_week' => SieuAm::completed()->whereBetween('updated_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => SieuAm::completed()->whereMonth('updated_at', now()->month)->count(),
        ];

        return view('staff.sieuam.completed', compact('sieuAms', 'stats'));
    }

    /**
     * Xem chi tiết
     */
    public function show(SieuAm $sieuAm)
    {
        $sieuAm->load(['benhAn.user', 'bacSi', 'loaiSieuAm']);
        return view('staff.sieuam.show', compact('sieuAm'));
    }

    /**
     * Form upload kết quả
     */
    public function uploadForm(SieuAm $sieuAm)
    {
        if ($sieuAm->trang_thai === SieuAm::STATUS_COMPLETED) {
            return back()->with('error', 'Siêu âm này đã có kết quả.');
        }

        $sieuAm->load(['benhAn.user', 'bacSi']);
        return view('staff.sieuam.upload', compact('sieuAm'));
    }

    /**
     * Upload file kết quả siêu âm
     */
    public function upload(Request $request, SieuAm $sieuAm)
    {
        if ($sieuAm->trang_thai === SieuAm::STATUS_COMPLETED) {
            return back()->with('error', 'Siêu âm này đã có kết quả.');
        }

        $validated = $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,dcm|max:20480', // 20MB
            'ket_qua' => 'required|string|max:2000',
        ], [
            'file.required' => 'Vui lòng chọn file kết quả',
            'file.mimes' => 'File phải là PDF, JPG, PNG hoặc DICOM',
            'file.max' => 'File không được vượt quá 20MB',
            'ket_qua.required' => 'Vui lòng nhập kết quả siêu âm',
        ]);

        // THÊM: Ưu tiên dùng workflow service để đồng bộ luồng & timestamps giống Xét nghiệm
        try {
            $this->workflowService->uploadUltrasoundResult(
                $sieuAm,
                $request->file('file'),
                (string) $validated['ket_qua'],
                'sieu_am_private'
            );

            return redirect()
                ->route('staff.sieuam.completed')
                ->with('success', 'Đã upload kết quả siêu âm thành công!');
        } catch (\Throwable $e) {
            // fallback xuống luồng cũ bên dưới (giữ nguyên code cũ theo rule chỉ thêm)
        }

        // Upload file
        $file = $request->file('file');
        $path = $file->store('sieu-am-results', 'sieu_am_private');

        // Xóa file cũ nếu có
        if ($sieuAm->file_path) {
            Storage::disk('sieu_am_private')->delete($sieuAm->file_path);
        }

        // Cập nhật
        $sieuAm->update([
            'file_path' => $path,
            'ket_qua' => $validated['ket_qua'],
            'trang_thai' => SieuAm::STATUS_COMPLETED,
            // THÊM: đồng bộ timestamps workflow
            'disk' => 'sieu_am_private',
            'ngay_hoan_thanh' => now(),
            'ngay_thuc_hien' => $sieuAm->ngay_thuc_hien ?: now(),
        ]);

        return redirect()
            ->route('staff.sieuam.completed')
            ->with('success', 'Đã upload kết quả siêu âm thành công!');
    }

    /**
     * Đánh dấu đang xử lý
     */
    public function markAsProcessing(SieuAm $sieuAm)
    {
        if ($sieuAm->trang_thai !== SieuAm::STATUS_PENDING) {
            return back()->with('error', 'Không thể chuyển trạng thái.');
        }

        // THÊM: dùng workflow service để set ngày_thuc_hien (nếu chưa có)
        try {
            $this->workflowService->markUltrasoundProcessing($sieuAm);
            return back()->with('success', 'Đã chuyển sang trạng thái đang xử lý.');
        } catch (\Throwable $e) {
            // fallback xuống luồng cũ
        }

        $sieuAm->update([
            'trang_thai' => SieuAm::STATUS_PROCESSING,
            // THÊM: set ngày_thuc_hien nếu chưa có
            'ngay_thuc_hien' => $sieuAm->ngay_thuc_hien ?: now(),
        ]);

        return back()->with('success', 'Đã chuyển sang trạng thái đang xử lý.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BacSi;
use App\Models\CaDieuChinhBacSi;
use App\Services\ShiftService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CaDieuChinhController extends Controller
{
    protected $shiftService;

    public function __construct(ShiftService $shiftService)
    {
        $this->shiftService = $shiftService;
    }

    /**
     * Hiển thị danh sách ca điều chỉnh của bác sĩ
     */
    public function index(BacSi $bacSi)
    {
        $caDieuChinhs = $bacSi->caDieuChinhs()
            ->orderBy('ngay', 'desc')
            ->orderBy('gio_bat_dau', 'desc')
            ->get();

        return view('admin.cadieuchinh.index', compact('bacSi', 'caDieuChinhs'));
    }

    /**
     * Thêm ca điều chỉnh mới
     */
    public function store(Request $request, BacSi $bacSi)
    {
        $validated = $request->validate([
            'ngay' => 'required|date|after_or_equal:today',
            'gio_bat_dau' => 'required|date_format:H:i',
            'gio_ket_thuc' => 'required|date_format:H:i|after:gio_bat_dau',
            'hanh_dong' => 'required|in:add,modify,cancel',
            'ly_do' => 'nullable|string|max:500',
        ]);

        // Tạo đối tượng Carbon để kiểm tra logic
        $startDateTime = Carbon::parse($validated['ngay'] . ' ' . $validated['gio_bat_dau']);
        $endDateTime = Carbon::parse($validated['ngay'] . ' ' . $validated['gio_ket_thuc']);

        // Kiểm tra nếu hành động là 'modify' hoặc 'cancel', phải nằm trong khung lịch làm việc
        if (in_array($validated['hanh_dong'], ['modify', 'cancel'])) {
            $this->shiftService->validateWithinWorkSchedule(
                $bacSi->id,
                $startDateTime,
                $endDateTime
            );
        }

        // Gọi service để kiểm tra xung đột
        $this->shiftService->checkConflictForAdjustment(
            $bacSi->id,
            $startDateTime,
            $endDateTime
        );

        $bacSi->caDieuChinhs()->create($validated);

        return back()->with('success', 'Thêm ca điều chỉnh thành công!');
    }

    /**
     * Cập nhật ca điều chỉnh
     */
    public function update(Request $request, CaDieuChinhBacSi $caDieuChinh)
    {
        $validated = $request->validate([
            'ngay' => 'required|date',
            'gio_bat_dau' => 'required|date_format:H:i',
            'gio_ket_thuc' => 'required|date_format:H:i|after:gio_bat_dau',
            'hanh_dong' => 'required|in:add,modify,cancel',
            'ly_do' => 'nullable|string|max:500',
        ]);

        // Tạo đối tượng Carbon để kiểm tra logic
        $startDateTime = Carbon::parse($validated['ngay'] . ' ' . $validated['gio_bat_dau']);
        $endDateTime = Carbon::parse($validated['ngay'] . ' ' . $validated['gio_ket_thuc']);

        // Kiểm tra nếu hành động là 'modify' hoặc 'cancel', phải nằm trong khung lịch làm việc
        if (in_array($validated['hanh_dong'], ['modify', 'cancel'])) {
            $this->shiftService->validateWithinWorkSchedule(
                $caDieuChinh->bac_si_id,
                $startDateTime,
                $endDateTime
            );
        }

        // Gọi service để kiểm tra xung đột, loại trừ chính nó
        $this->shiftService->checkConflictForAdjustment(
            $caDieuChinh->bac_si_id,
            $startDateTime,
            $endDateTime,
            $caDieuChinh->id
        );

        $caDieuChinh->update($validated);

        return back()->with('success', 'Cập nhật ca điều chỉnh thành công!');
    }

    /**
     * Xóa ca điều chỉnh
     */
    public function destroy(CaDieuChinhBacSi $caDieuChinh)
    {
        $caDieuChinh->delete();

        return back()->with('success', 'Xóa ca điều chỉnh thành công!');
    }
}

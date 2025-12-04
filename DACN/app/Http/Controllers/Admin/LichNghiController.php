<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BacSi;
use App\Models\LichNghi;
use App\Services\ShiftService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LichNghiController extends Controller
{
    protected $shiftService;

    public function __construct(ShiftService $shiftService)
    {
        $this->shiftService = $shiftService;
    }

    public function index(BacSi $bacSi)
    {
        $lichNghis = $bacSi->lichNghis()
            ->orderBy('ngay', 'desc')
            ->get();

        return view('admin.lichnghi.index', compact('bacSi', 'lichNghis'));
    }

    public function store(Request $request, BacSi $bacSi)
    {
        $validated = $request->validate([
            'ngay' => 'required|date|after_or_equal:today',
            'bat_dau' => 'required|date_format:H:i',
            'ket_thuc' => 'required|date_format:H:i|after:bat_dau',
            'ly_do' => 'nullable|string|max:500',
        ]);

        // Tạo đối tượng Carbon để kiểm tra logic
        $startDateTime = Carbon::parse($validated['ngay'] . ' ' . $validated['bat_dau']);
        $endDateTime = Carbon::parse($validated['ngay'] . ' ' . $validated['ket_thuc']);

        // Gọi service để kiểm tra xung đột
        $this->shiftService->checkConflictForLeave(
            $bacSi->id,
            $startDateTime,
            $endDateTime
        );

        // Nếu không có lỗi, tạo lịch nghỉ
        $bacSi->lichNghis()->create($validated);

        return back()->with('success', 'Thêm lịch nghỉ thành công!');
    }

    public function destroy(LichNghi $lichNghi)
    {
        // Chính sách phân quyền có thể được thêm ở đây nếu cần
        // $this->authorize('delete', $lichNghi);
        $lichNghi->delete();

        return back()->with('success', 'Xóa lịch nghỉ thành công!');
    }
}

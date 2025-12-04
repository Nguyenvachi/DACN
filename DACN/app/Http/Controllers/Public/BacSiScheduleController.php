<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BacSi;
use App\Services\ShiftService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BacSiScheduleController extends Controller
{
    protected $shiftService;

    public function __construct(ShiftService $shiftService)
    {
        $this->shiftService = $shiftService;
    }

    /**
     * Hiển thị lịch rảnh của bác sĩ theo tuần
     *
     * @param BacSi $bacSi
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function weeklySchedule(BacSi $bacSi, Request $request)
    {
        // Lấy tuần hiện tại hoặc tuần được chỉ định
        $weekStart = $request->get('week_start')
            ? Carbon::parse($request->get('week_start'))->startOfWeek()
            : Carbon::now()->startOfWeek();

        $weekEnd = $weekStart->copy()->endOfWeek();

        // Lấy tất cả slot rảnh trong tuần
        $slots = $this->shiftService->getAvailableSlots(
            $bacSi->id,
            $weekStart,
            $weekEnd,
            30 // 30 phút/slot
        );

        // Nhóm slots theo ngày
        $slotsByDate = collect($slots)->groupBy('date');

        return view('public.bacsi.weekly-schedule', compact('bacSi', 'weekStart', 'weekEnd', 'slotsByDate'));
    }

    /**
     * API: Lấy N slot kế tiếp có sẵn
     *
     * @param BacSi $bacSi
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function nextAvailableSlots(BacSi $bacSi, Request $request)
    {
        $count = $request->get('count', 10);
        $count = min($count, 50); // Giới hạn tối đa 50 slots

        $slots = $this->shiftService->getNextAvailableSlots($bacSi->id, $count);

        return response()->json([
            'success' => true,
            'bac_si' => [
                'id' => $bacSi->id,
                'ten' => $bacSi->ten,
                'chuyen_khoa' => $bacSi->chuyenKhoa->ten ?? null,
            ],
            'slots' => $slots,
        ]);
    }
}

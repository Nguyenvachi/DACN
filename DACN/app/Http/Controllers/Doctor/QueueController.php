<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\LichHen;
use App\Models\BacSi;
use App\Models\BenhAn;
use App\Services\MedicalWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Controller quản lý Hàng đợi khám bệnh
 * - Danh sách bệnh nhân đã check-in (chờ khám)
 * - Bắt đầu khám (tạo bệnh án tự động)
 * - Quản lý trạng thái khám bệnh
 */
class QueueController extends Controller
{
    protected $workflowService;

    public function __construct(MedicalWorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    /**
     * Danh sách bệnh nhân trong hàng đợi (đã check-in)
     */
    public function index(Request $request)
    {
        $bacSi = BacSi::where('user_id', auth()->id())->first();

        if (!$bacSi) {
            return redirect()->route('doctor.dashboard')
                ->with('error', 'Không tìm thấy thông tin bác sĩ.');
        }

        // Lấy danh sách bệnh nhân đã check-in (chờ khám hoặc đang khám)
        $queueQuery = LichHen::with(['user.patientProfile', 'dichVu'])
            ->where('bac_si_id', $bacSi->id)
            ->whereIn('trang_thai', [
                LichHen::STATUS_CHECKED_IN_VN,
                LichHen::STATUS_IN_PROGRESS_VN,
                LichHen::STATUS_CONFIRMED_VN,
            ])
            ->whereDate('ngay_hen', today()) // Chỉ lấy hôm nay
            ->orderBy('checked_in_at')
            ->orderBy('thoi_gian_hen');

        $queue = $queueQuery->get();

        // Phân loại theo trạng thái
        $waitingQueue = $queue->where('trang_thai', LichHen::STATUS_CHECKED_IN_VN); // Đã đến, đang chờ được gọi vào khám
        $inProgressQueue = $queue->where('trang_thai', LichHen::STATUS_IN_PROGRESS_VN); // Đang được khám (đã vào phòng khám)
        $confirmedToday = $queue->where('trang_thai', LichHen::STATUS_CONFIRMED_VN); // Đã đặt lịch và xác nhận nhưng chưa đến phòng khám (chưa check-in)

        // Thống kê tổng quan
        $stats = [
            'waiting' => $waitingQueue->count(), // Số bệnh nhân đang chờ ở ngoài
            'in_progress' => $inProgressQueue->count(), // Số bệnh nhân đang được khám
            'confirmed_today' => $confirmedToday->count(), // Số lịch hẹn đã xác nhận nhưng bệnh nhân chưa đến
            'avg_wait_time' => $this->calculateAverageWaitTime($bacSi->id), // Thời gian chờ trung bình (phút) từ lúc check-in đến lúc bắt đầu khám
        ];

        return view('doctor.queue.index', compact('queue', 'waitingQueue', 'inProgressQueue', 'confirmedToday', 'stats', 'bacSi'));
    }

    /**
     * Bắt đầu khám (tạo bệnh án tự động hoặc chuyển sang tạo bệnh án)
     */
    public function startExamination(Request $request, LichHen $lichHen)
    {
        // Check quyền
        $bacSi = BacSi::where('user_id', auth()->id())->first();
        if (!$bacSi || $lichHen->bac_si_id !== $bacSi->id) {
            return back()->with('error', 'Bạn không có quyền khám bệnh nhân này.');
        }

        // Check trạng thái (phải đã check-in hoặc đã xác nhận)
        if (!in_array($lichHen->trang_thai, [
            LichHen::STATUS_CHECKED_IN_VN,
            LichHen::STATUS_CONFIRMED_VN
        ])) {
            return back()->with('error', 'Bệnh nhân chưa check-in hoặc lịch hẹn không hợp lệ.');
        }

        try {
            DB::beginTransaction();

            // Tạo bệnh án tự động với thông tin cơ bản
            $benhAn = $this->workflowService->startExamination($lichHen, [
                'tieu_de' => 'Khám ' . ($lichHen->dichVu->ten_dich_vu ?? 'bệnh'),
                'trieu_chung' => $request->input('trieu_chung', ''),
                'chuan_doan' => '',
                'dieu_tri' => '',
                'ghi_chu' => 'Bệnh án được tạo tự động khi bắt đầu khám',
            ]);

            DB::commit();

            // Chuyển sang trang edit bệnh án để bác sĩ nhập thông tin
            return redirect()->route('doctor.benhan.edit', $benhAn->id)
                ->with('success', 'Đã bắt đầu khám bệnh. Vui lòng nhập thông tin khám bệnh.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error starting examination: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Check-in thủ công (nếu bệnh nhân đến nhưng chưa check-in)
     */
    public function checkIn(LichHen $lichHen)
    {
        $bacSi = BacSi::where('user_id', auth()->id())->first();
        if (!$bacSi || $lichHen->bac_si_id !== $bacSi->id) {
            return back()->with('error', 'Bạn không có quyền check-in cho bệnh nhân này.');
        }

        if ($lichHen->trang_thai !== LichHen::STATUS_CONFIRMED_VN) {
            return back()->with('error', 'Lịch hẹn chưa được xác nhận hoặc đã được xử lý.');
        }

        try {
            $success = $this->workflowService->checkInAppointment($lichHen);

            if ($success) {
                return back()->with('success', 'Đã check-in thành công. Bệnh nhân đã vào hàng đợi.');
            }

            return back()->with('error', 'Không thể check-in.');
        } catch (\Exception $e) {
            Log::error('Error checking in: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Tính thời gian chờ trung bình (từ lúc check-in đến lúc bắt đầu khám)
     */
    private function calculateAverageWaitTime($bacSiId)
    {
        // Lấy các lịch hẹn đã bắt đầu khám hoặc đã hoàn thành hôm nay
        $processedToday = LichHen::where('bac_si_id', $bacSiId)
            ->whereDate('ngay_hen', today())
            ->whereIn('trang_thai', [
                LichHen::STATUS_IN_PROGRESS_VN,
                LichHen::STATUS_COMPLETED_VN
            ])
            ->whereNotNull('checked_in_at')
            ->get();

        if ($processedToday->isEmpty()) {
            return 0;
        }

        $totalWaitMinutes = 0;
        $countWithData = 0;

        foreach ($processedToday as $appt) {
            $checkedInAt = Carbon::parse($appt->checked_in_at);

            // Ưu tiên lấy thời gian từ bệnh án (khi bắt đầu khám)
            if ($appt->benhAn && $appt->benhAn->created_at) {
                $startedAt = Carbon::parse($appt->benhAn->created_at);
                $totalWaitMinutes += $checkedInAt->diffInMinutes($startedAt);
                $countWithData++;
            }
            // Nếu không có bệnh án, dùng updated_at khi chuyển sang IN_PROGRESS
            elseif ($appt->trang_thai === LichHen::STATUS_IN_PROGRESS_VN && $appt->updated_at) {
                $startedAt = Carbon::parse($appt->updated_at);
                $totalWaitMinutes += $checkedInAt->diffInMinutes($startedAt);
                $countWithData++;
            }
            // Nếu đã hoàn thành, dùng completed_at (ít chính xác hơn)
            elseif ($appt->trang_thai === LichHen::STATUS_COMPLETED_VN && $appt->completed_at) {
                $completedAt = Carbon::parse($appt->completed_at);
                $totalWaitMinutes += $checkedInAt->diffInMinutes($completedAt);
                $countWithData++;
            }
        }

        return $countWithData > 0 ? round($totalWaitMinutes / $countWithData) : 0;
    }

    /**
     * Lấy số bệnh nhân trong hàng đợi (API cho realtime update)
     */
    public function getQueueCount()
    {
        $bacSi = BacSi::where('user_id', auth()->id())->first();

        if (!$bacSi) {
            return response()->json(['count' => 0]);
        }

        $count = LichHen::where('bac_si_id', $bacSi->id)
            ->where('trang_thai', LichHen::STATUS_CHECKED_IN_VN)
            ->whereDate('ngay_hen', today())
            ->count();

        return response()->json(['count' => $count]);
    }
}

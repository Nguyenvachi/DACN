<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\LichHen;
use App\Models\BacSi;
use App\Models\DichVu;
use App\Services\MedicalWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Controller quản lý lịch hẹn cho Bác sĩ
 * - Xem lịch hẹn chờ xác nhận
 * - Xác nhận / Từ chối lịch hẹn
 * - Xem lịch hẹn đã xác nhận
 */
class LichHenController extends Controller
{
    protected $workflowService;

    public function __construct(MedicalWorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    /**
     * Danh sách lịch hẹn chờ xác nhận
     */
    public function pending(Request $request)
    {
        $bacSi = BacSi::where('user_id', auth()->id())->first();

        if (!$bacSi) {
            return redirect()->route('doctor.dashboard')
                ->with('error', 'Không tìm thấy thông tin bác sĩ.');
        }

        // Query lịch hẹn chờ xác nhận
        $query = LichHen::with(['user', 'dichVu'])
            ->where('bac_si_id', $bacSi->id)
            ->where('trang_thai', 'Chờ xác nhận')
            ->orderBy('ngay_hen')
            ->orderBy('thoi_gian_hen');

        // Filter theo ngày (nếu có)
        if ($request->filled('from_date')) {
            $query->whereDate('ngay_hen', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('ngay_hen', '<=', $request->to_date);
        }

        // Filter theo dịch vụ
        if ($request->filled('dich_vu_id')) {
            $query->where('dich_vu_id', $request->dich_vu_id);
        }

        $appointments = $query->paginate(15)->withQueryString();

        // Thống kê
        $stats = [
            'total_pending' => LichHen::where('bac_si_id', $bacSi->id)
                ->where('trang_thai', 'Chờ xác nhận')
                ->count(),
            'today_pending' => LichHen::where('bac_si_id', $bacSi->id)
                ->where('trang_thai', 'Chờ xác nhận')
                ->whereDate('ngay_hen', today())
                ->count(),
            'this_week' => LichHen::where('bac_si_id', $bacSi->id)
                ->where('trang_thai', 'Chờ xác nhận')
                ->whereBetween('ngay_hen', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ])
                ->count(),
        ];

        // Danh sách dịch vụ cho filter
        $dichVus = \App\Models\DichVu::orderBy('ten_dich_vu')->get();

        return view('doctor.lichhen.pending', compact('appointments', 'stats', 'dichVus', 'bacSi'));
    }

    /**
     * Xác nhận lịch hẹn
     */
    public function confirm(Request $request, LichHen $lichHen)
    {
        // Check quyền
        $bacSi = BacSi::where('user_id', auth()->id())->first();
        if (!$bacSi || $lichHen->bac_si_id !== $bacSi->id) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Bạn không có quyền xác nhận lịch hẹn này.'], 403);
            }
            return back()->with('error', 'Bạn không có quyền xác nhận lịch hẹn này.');
        }

        // Check trạng thái
        if ($lichHen->trang_thai !== 'Chờ xác nhận') {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Lịch hẹn này đã được xử lý rồi.'], 400);
            }
            return back()->with('error', 'Lịch hẹn này đã được xử lý rồi.');
        }

        try {
            DB::beginTransaction();

            // Sử dụng MedicalWorkflowService
            $success = $this->workflowService->confirmAppointment($lichHen, auth()->user()->name);

            if ($success) {
                DB::commit();

                // JSON request (AJAX/Fetch API)
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Đã xác nhận lịch hẹn thành công!'
                    ]);
                }

                return back()->with('success', 'Đã xác nhận lịch hẹn thành công! Email đã được gửi tới bệnh nhân.');
            }

            DB::rollBack();

            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Không thể xác nhận lịch hẹn này.'], 500);
            }
            return back()->with('error', 'Không thể xác nhận lịch hẹn này.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error confirming appointment: ' . $e->getMessage());

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Từ chối lịch hẹn
     */
    public function reject(Request $request, LichHen $lichHen)
    {
        // Check quyền
        $bacSi = BacSi::where('user_id', auth()->id())->first();
        if (!$bacSi || $lichHen->bac_si_id !== $bacSi->id) {
            return back()->with('error', 'Bạn không có quyền từ chối lịch hẹn này.');
        }

        // Check trạng thái
        if ($lichHen->trang_thai !== 'Chờ xác nhận') {
            return back()->with('error', 'Lịch hẹn này đã được xử lý rồi.');
        }

        $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $reason = $request->reason ?? 'Bác sĩ không thể tiếp nhận lịch hẹn này';
            $success = $this->workflowService->cancelAppointment($lichHen, $reason);

            if ($success) {
                DB::commit();

                // AJAX request
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Đã từ chối lịch hẹn.'
                    ]);
                }

                return back()->with('success', 'Đã từ chối lịch hẹn. Email thông báo đã được gửi tới bệnh nhân.');
            }

            DB::rollBack();
            return back()->with('error', 'Không thể từ chối lịch hẹn này.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error rejecting appointment: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Xem chi tiết lịch hẹn (modal/page)
     */
    public function show(LichHen $lichHen)
    {
        // Check quyền
        $bacSi = BacSi::where('user_id', auth()->id())->first();
        if (!$bacSi || $lichHen->bac_si_id !== $bacSi->id) {
            abort(403, 'Bạn không có quyền xem lịch hẹn này.');
        }

        $lichHen->load(['user.patientProfile', 'dichVu', 'hoaDon', 'conversation']);

        return view('doctor.lichhen.show', compact('lichHen'));
    }

    /**
     * Danh sách lịch hẹn đã xác nhận (hôm nay hoặc tuần này)
     */
    public function confirmed(Request $request)
    {
        $bacSi = BacSi::where('user_id', auth()->id())->first();

        if (!$bacSi) {
            return redirect()->route('doctor.dashboard')
                ->with('error', 'Không tìm thấy thông tin bác sĩ.');
        }

        $view = $request->get('view', 'today'); // today, week, all

        $query = LichHen::with(['user', 'dichVu'])
            ->where('bac_si_id', $bacSi->id)
            ->where('trang_thai', 'Đã xác nhận');

        if ($view === 'today') {
            $query->whereDate('ngay_hen', today());
        } elseif ($view === 'week') {
            $query->whereBetween('ngay_hen', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ]);
        }

        $query->orderBy('ngay_hen')->orderBy('thoi_gian_hen');

        $appointments = $query->paginate(20)->appends($request->all());

        return view('doctor.lichhen.confirmed', compact('appointments', 'view', 'bacSi'));
    }

    /**
     * Hoàn thành khám bệnh
     */
    public function complete(Request $request, LichHen $lichHen)
    {
        $bacSi = BacSi::where('user_id', auth()->id())->first();

        if (!$bacSi || $lichHen->bac_si_id !== $bacSi->id) {
            return back()->with('error', 'Bạn không có quyền');
        }

        if ($lichHen->trang_thai !== 'Đang khám') {
            return back()->with('error', 'Chỉ có thể hoàn thành lịch hẹn đang khám');
        }

        try {
            // Sử dụng service để hoàn thành
            $this->workflowService->completeExamination($lichHen, [
                'chuan_doan' => $request->input('chuan_doan'),
                'dieu_tri' => $request->input('dieu_tri'),
                'ghi_chu' => $request->input('ghi_chu'),
            ]);

            Log::info("Bác sĩ #{$bacSi->id} đã hoàn thành khám lịch hẹn #{$lichHen->id}");

            return redirect()
                ->route('doctor.queue.index')
                ->with('success', 'Đã hoàn thành khám bệnh. Hệ thống đã tạo hóa đơn tự động.');

        } catch (\Exception $e) {
            Log::error("Lỗi khi hoàn thành khám: " . $e->getMessage());

            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}


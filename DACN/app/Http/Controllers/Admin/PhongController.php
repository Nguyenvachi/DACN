<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Phong;
use App\Models\LoaiPhong;
use App\Models\BacSi;
use App\Services\RoomAvailabilityService;
use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * Controller quản lý phòng khám
 * Parent file: app/Http/Controllers/Admin/PhongController.php
 */
class PhongController extends Controller
{
    protected $roomService;

    public function __construct(RoomAvailabilityService $roomService)
    {
        $this->roomService = $roomService;
    }

    public function index()
    {
        $phongs = Phong::with('loaiPhong')->withCount('bacSis')->orderBy('ten')->paginate(20);
        return view('admin.phong.index', compact('phongs'));
    }

    public function create()
    {
        $phong = new Phong();
        $loaiPhongs = LoaiPhong::orderBy('ten')->get();
        $bacSis = BacSi::orderBy('ho_ten')->get();
        return view('admin.phong.form', compact('phong', 'loaiPhongs', 'bacSis'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ten' => 'required|string|max:255',
            'loai_phong_id' => 'required|integer|exists:loai_phongs,id',
            'mo_ta' => 'nullable|string',
            'trang_thai' => 'nullable|in:Sẵn sàng,Đang sử dụng,Bảo trì,Tạm ngưng',
            'vi_tri' => 'nullable|string|max:255',
            'dien_tich' => 'nullable|numeric|min:0',
            'suc_chua' => 'nullable|integer|min:1',
            'bac_si_ids' => 'array',
            'bac_si_ids.*' => 'integer|exists:bac_sis,id',
        ]);

        // Set default trạng thái
        $data['trang_thai'] = $data['trang_thai'] ?? 'Sẵn sàng';

        $phong = Phong::create($data);
        if (!empty($data['bac_si_ids'])) {
            $phong->bacSis()->sync($data['bac_si_ids']);
        }
        return redirect()->route('admin.phong.index')->with('success', 'Đã tạo phòng');
    }

    public function edit(Phong $phong)
    {
        $loaiPhongs = LoaiPhong::orderBy('ten')->get();
        $bacSis = BacSi::orderBy('ho_ten')->get();
        return view('admin.phong.form', compact('phong', 'loaiPhongs', 'bacSis'));
    }

    public function update(Request $request, Phong $phong)
    {
        $data = $request->validate([
            'ten' => 'required|string|max:255',
            'loai_phong_id' => 'required|integer|exists:loai_phongs,id',
            'mo_ta' => 'nullable|string',
            'trang_thai' => 'nullable|in:Sẵn sàng,Đang sử dụng,Bảo trì,Tạm ngưng',
            'vi_tri' => 'nullable|string|max:255',
            'dien_tich' => 'nullable|numeric|min:0',
            'suc_chua' => 'nullable|integer|min:1',
            'bac_si_ids' => 'array',
            'bac_si_ids.*' => 'integer|exists:bac_sis,id',
        ]);
        $phong->update($data);
        $phong->bacSis()->sync($data['bac_si_ids'] ?? []);
        return redirect()->route('admin.phong.index')->with('success', 'Đã cập nhật phòng');
    }

    public function destroy(Phong $phong)
    {
        $phong->delete();
        return redirect()->route('admin.phong.index')->with('success', 'Đã xóa phòng');
    }

    // ==================== MỞ RỘNG: METHODS MỚI (Parent: app/Http/Controllers/Admin/PhongController.php) ====================

    /**
     * Hiển thị sơ đồ phòng realtime
     */
    public function diagram()
    {
        $phongs = Phong::with(['bacSis', 'lichLamViecs'])
            ->orderBy('vi_tri')
            ->orderBy('ten')
            ->get();

        // Nhóm theo vị trí (tầng)
        $roomsByFloor = $phongs->groupBy('vi_tri');

        return view('admin.phong.diagram', compact('phongs', 'roomsByFloor'));
    }

    /**
     * API: Kiểm tra xung đột phòng
     */
    public function checkConflict(Request $request)
    {
        $request->validate([
            'phong_id' => 'required|integer|exists:phongs,id',
            'date' => 'required|date',
            'time_start' => 'required|date_format:H:i',
            'time_end' => 'required|date_format:H:i|after:time_start',
            'ignore_lich_hen_id' => 'nullable|integer',
            'ignore_lich_lam_viec_id' => 'nullable|integer',
        ]);

        $start = Carbon::parse($request->date . ' ' . $request->time_start);
        $end = Carbon::parse($request->date . ' ' . $request->time_end);

        $result = $this->roomService->checkRoomConflict(
            $request->phong_id,
            $start,
            $end,
            $request->ignore_lich_hen_id,
            $request->ignore_lich_lam_viec_id
        );

        return response()->json($result);
    }

    /**
     * API: Lấy trạng thái phòng tại thời điểm
     */
    public function getStatus(Phong $phong, Request $request)
    {
        $datetime = $request->has('datetime')
            ? Carbon::parse($request->datetime)
            : now();

        $status = $this->roomService->getRoomStatus($phong->id, $datetime);

        return response()->json([
            'phong_id' => $phong->id,
            'ten' => $phong->ten,
            'status' => $status,
            'trang_thai_db' => $phong->trang_thai,
            'datetime' => $datetime->toIso8601String()
        ]);
    }

    /**
     * API: Lấy danh sách phòng khả dụng
     */
    public function available(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'time_start' => 'required|date_format:H:i',
            'time_end' => 'required|date_format:H:i|after:time_start',
        ]);

        $start = Carbon::parse($request->date . ' ' . $request->time_start);
        $end = Carbon::parse($request->date . ' ' . $request->time_end);

        $availableRooms = $this->roomService->getAvailableRooms($start, $end);

        return response()->json([
            'count' => $availableRooms->count(),
            'rooms' => $availableRooms->map(function ($room) {
                return [
                    'id' => $room->id,
                    'ten' => $room->ten,
                    'loai' => $room->loai,
                    'vi_tri' => $room->vi_tri,
                    'trang_thai' => $room->trang_thai,
                ];
            })
        ]);
    }

    /**
     * Cập nhật trạng thái phòng (AJAX)
     */
    public function updateStatus(Phong $phong, Request $request)
    {
        $request->validate([
            'trang_thai' => 'required|in:Sẵn sàng,Đang sử dụng,Bảo trì,Tạm ngưng'
        ]);

        $phong->update(['trang_thai' => $request->trang_thai]);

        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật trạng thái phòng',
            'phong' => [
                'id' => $phong->id,
                'ten' => $phong->ten,
                'trang_thai' => $phong->trang_thai,
                'status_badge' => $phong->status_badge,
                'status_icon' => $phong->status_icon,
            ]
        ]);
    }

    /**
     * Xem thống kê sử dụng phòng
     */
    public function statistics(Phong $phong, Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        $stats = $this->roomService->getRoomUsageStats($phong->id, $date);

        // Lấy lịch sử tuần này
        $from = Carbon::parse($date)->startOfWeek();
        $to = Carbon::parse($date)->endOfWeek();
        $history = $this->roomService->getRoomHistory($phong->id, $from, $to);

        return view('admin.phong.statistics', compact('phong', 'stats', 'history', 'date'));
    }

    /**
     * Gợi ý phòng cho bác sĩ
     */
    public function suggestForDoctor(Request $request)
    {
        $request->validate([
            'bac_si_id' => 'required|integer|exists:bac_sis,id',
            'date' => 'required|date',
            'time_start' => 'required|date_format:H:i',
            'time_end' => 'required|date_format:H:i|after:time_start',
        ]);

        $start = Carbon::parse($request->date . ' ' . $request->time_start);
        $end = Carbon::parse($request->date . ' ' . $request->time_end);

        $suggestedRooms = $this->roomService->suggestRoomsForDoctor(
            $request->bac_si_id,
            $start,
            $end
        );

        return response()->json([
            'count' => $suggestedRooms->count(),
            'rooms' => $suggestedRooms->map(function ($room, $index) {
                return [
                    'id' => $room->id,
                    'ten' => $room->ten,
                    'loai' => $room->loai,
                    'vi_tri' => $room->vi_tri,
                    'priority' => $index === 0 ? 'preferred' : 'available'
                ];
            })
        ]);
    }
}

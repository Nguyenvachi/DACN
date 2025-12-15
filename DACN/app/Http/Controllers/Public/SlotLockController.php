<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SlotLockService;
use App\Models\SlotLock;
use Illuminate\Support\Facades\Auth;
use App\Services\LichKhamService;
use Carbon\Carbon;

class SlotLockController extends Controller
{
    protected $lockService;
    protected $lichService;

    public function __construct(SlotLockService $lockService, LichKhamService $lichService)
    {
        $this->lockService = $lockService;
        $this->lichService = $lichService;
    }

    public function lock(Request $request)
    {
        $request->validate([
            'bac_si_id' => 'required|integer|exists:bac_sis,id',
            'ngay' => 'required|date_format:Y-m-d',
            'gio' => 'required',
            'duration' => 'nullable|integer',
        ]);

        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập để đặt giữ chỗ'], 401);
        }

        $userId = Auth::id();
        $bacSiId = $request->bac_si_id;
        $ngay = $request->ngay;
        $gio = $request->gio;
        $duration = (int)($request->duration ?? 30);

        // Double-check the doctor has no conflict
        $check = $this->lichService->hasConflictForDoctor($bacSiId, $ngay, $gio, $duration, null);
        if ($check['conflict']) {
            return response()->json(['success' => false, 'message' => $check['details'][0] ?? 'Không khả dụng'], 409);
        }

        // Clean expired locks from DB
        try {
            SlotLock::where('expires_at', '<=', Carbon::now())->delete();
        } catch (\Throwable $e) {
            // ignore
        }

        $existing = SlotLock::where('bac_si_id', $bacSiId)->where('ngay', $ngay)->where('gio', $gio)->first();
        if ($existing && !$existing->isExpired() && $existing->user_id != $userId) {
            return response()->json(['success' => false, 'message' => 'Khung giờ đã được giữ bởi người khác', 'owner' => $existing->user_id, 'until' => $existing->expires_at], 409);
        }

        $expiresAt = Carbon::now()->addSeconds(300);
        $lock = SlotLock::updateOrCreate(
            ['bac_si_id' => $bacSiId, 'ngay' => $ngay, 'gio' => $gio],
            ['user_id' => $userId, 'expires_at' => $expiresAt]
        );

        return response()->json(['success' => true, 'owner' => $userId, 'locked_until' => $lock->expires_at]);
    }

    public function unlock(Request $request)
    {
        $request->validate([
            'bac_si_id' => 'required|integer|exists:bac_sis,id',
            'ngay' => 'required|date_format:Y-m-d',
            'gio' => 'required',
        ]);
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        $userId = Auth::id();
        // Delete only if user owns the lock
        $deleted = SlotLock::where('bac_si_id', $request->bac_si_id)
            ->where('ngay', $request->ngay)
            ->where('gio', $request->gio)
            ->where('user_id', $userId)
            ->delete();

        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Không thể giải phóng; có thể bạn không giữ slot này'], 403);
        }

        return response()->json(['success' => true]);
    }

    public function check(Request $request)
    {
        $request->validate([
            'bac_si_id' => 'required|integer|exists:bac_sis,id',
            'ngay' => 'required|date_format:Y-m-d',
            'gio' => 'required',
        ]);
        $lock = SlotLock::where('bac_si_id', $request->bac_si_id)
            ->where('ngay', $request->ngay)
            ->where('gio', $request->gio)
            ->first();
        return response()->json(['success' => true, 'lock' => $lock]);
    }
}

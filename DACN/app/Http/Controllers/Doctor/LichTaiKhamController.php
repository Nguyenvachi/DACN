<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\LichTaiKham;
use App\Models\BenhAn;
use App\Models\BacSi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LichTaiKhamController extends Controller
{
    /**
     * Danh sách lịch tái khám
     */
    public function index(Request $request)
    {
        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi) {
            abort(403);
        }

        $query = LichTaiKham::with(['benhNhan', 'benhAn'])
            ->where('bac_si_id', $bacSi->id);

        // Filter by status
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Filter by date range
        if ($request->filled('tu_ngay')) {
            $query->where('ngay_hen', '>=', $request->tu_ngay);
        }
        if ($request->filled('den_ngay')) {
            $query->where('ngay_hen', '<=', $request->den_ngay);
        }

        $lichTaiKhams = $query->orderBy('ngay_hen', 'desc')->paginate(20);

        return view('doctor.lich-tai-kham.index', compact('lichTaiKhams'));
    }

    /**
     * Form tạo lịch tái khám
     */
    public function create(Request $request)
    {
        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi) {
            abort(403);
        }

        $benhAnId = $request->input('benh_an_id');
        $benhAn = null;

        if ($benhAnId) {
            $benhAn = BenhAn::with('user')->findOrFail($benhAnId);

            // Check permission
            if ($benhAn->bac_si_id !== $bacSi->id) {
                abort(403, 'Bạn không có quyền tạo lịch tái khám cho bệnh án này');
            }
        }

        return view('doctor.lich-tai-kham.create', compact('benhAn'));
    }

    /**
     * Lưu lịch tái khám
     */
    public function store(Request $request)
    {
        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi) {
            abort(403);
        }

        $validated = $request->validate([
            'benh_an_id' => 'required|exists:benh_ans,id',
            'ngay_hen' => 'required|date|after_or_equal:today',
            'gio_hen' => 'nullable|date_format:H:i',
            'ly_do' => 'required|string',
            'luu_y' => 'nullable|string',
            'ghi_chu' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $benhAn = BenhAn::findOrFail($validated['benh_an_id']);

            // Check permission
            if ($benhAn->bac_si_id !== $bacSi->id) {
                abort(403);
            }

            LichTaiKham::create([
                'benh_an_id' => $validated['benh_an_id'],
                'bac_si_id' => $bacSi->id,
                'benh_nhan_id' => $benhAn->user_id,
                'ngay_hen' => $validated['ngay_hen'],
                'gio_hen' => $validated['gio_hen'] ?? null,
                'ly_do' => $validated['ly_do'],
                'luu_y' => $validated['luu_y'] ?? null,
                'ghi_chu' => $validated['ghi_chu'] ?? null,
                'trang_thai' => 'Đã hẹn',
            ]);

            DB::commit();

            return redirect()->route('doctor.benhan.edit', $benhAn->id)
                ->with('success', 'Đã tạo lịch tái khám thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Xem chi tiết lịch tái khám
     */
    public function show(LichTaiKham $lichTaiKham)
    {
        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi || $lichTaiKham->bac_si_id !== $bacSi->id) {
            abort(403);
        }

        $lichTaiKham->load(['benhNhan', 'benhAn', 'bacSi']);

        return view('doctor.lich-tai-kham.show', compact('lichTaiKham'));
    }

    /**
     * Cập nhật trạng thái
     */
    public function updateStatus(Request $request, LichTaiKham $lichTaiKham)
    {
        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi || $lichTaiKham->bac_si_id !== $bacSi->id) {
            abort(403);
        }

        $validated = $request->validate([
            'trang_thai' => 'required|in:Đã hẹn,Đã xác nhận,Đã khám,Đã hủy',
            'ghi_chu' => 'nullable|string',
        ]);

        try {
            $updateData = ['trang_thai' => $validated['trang_thai']];

            if (isset($validated['ghi_chu'])) {
                $updateData['ghi_chu'] = $validated['ghi_chu'];
            }

            if ($validated['trang_thai'] === 'Đã xác nhận') {
                $updateData['ngay_xac_nhan'] = now();
            }

            if ($validated['trang_thai'] === 'Đã khám') {
                $updateData['ngay_kham_thuc_te'] = now();
            }

            $lichTaiKham->update($updateData);

            return back()->with('success', 'Đã cập nhật trạng thái lịch tái khám!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Hủy lịch tái khám
     */
    public function destroy(LichTaiKham $lichTaiKham)
    {
        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi || $lichTaiKham->bac_si_id !== $bacSi->id) {
            abort(403);
        }

        try {
            $lichTaiKham->update(['trang_thai' => 'Đã hủy']);

            return back()->with('success', 'Đã hủy lịch tái khám!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}

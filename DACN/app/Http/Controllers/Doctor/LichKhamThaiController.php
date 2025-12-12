<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\BacSi;
use App\Models\TheoDoiThaiKy;
use App\Models\LichKhamThai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LichKhamThaiController extends Controller
{
    /**
     * Form thêm lịch khám thai
     */
    public function create(TheoDoiThaiKy $theoDoiThaiKy)
    {
        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi) {
            abort(403);
        }

        // Tính tuổi thai hiện tại
        $tuoiThai = $theoDoiThaiKy->tuoiThaiHienTai();

        return view('doctor.lich-kham-thai.create', compact('theoDoiThaiKy', 'tuoiThai'));
    }

    /**
     * Lưu lịch khám thai
     */
    public function store(Request $request, TheoDoiThaiKy $theoDoiThaiKy)
    {
        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi) {
            return back()->with('error', 'Không tìm thấy thông tin bác sĩ.');
        }

        $validated = $request->validate([
            'ngay_kham' => 'required|date',
            'tuan_thai' => 'required|integer|min:0|max:42',
            'ngay_thai' => 'nullable|integer|min:0|max:6',
            'can_nang' => 'nullable|numeric|min:0',
            'huyet_ap_tam_thu' => 'nullable|numeric|min:0',
            'huyet_ap_tam_truong' => 'nullable|numeric|min:0',
            'nhiet_do' => 'nullable|numeric|min:0',
            'nhip_tim_me' => 'nullable|integer|min:0',
            'chieu_cao_tu_cung' => 'nullable|numeric|min:0',
            'vong_bung' => 'nullable|numeric|min:0',
            'nhip_tim_thai' => 'nullable|integer|min:0',
            'vi_tri_thai' => 'nullable|string|max:100',
            'trieu_chung' => 'nullable|string',
            'kham_lam_sang' => 'nullable|string',
            'chi_dinh' => 'nullable|string',
            'danh_gia' => 'nullable|string',
            'tu_van' => 'nullable|string',
            'hen_kham_lai' => 'nullable|date',
            'ghi_chu' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            LichKhamThai::create([
                'theo_doi_thai_ky_id' => $theoDoiThaiKy->id,
                'bac_si_id' => $bacSi->id,
                'ngay_kham' => $validated['ngay_kham'],
                'tuan_thai' => $validated['tuan_thai'],
                'ngay_thai' => $validated['ngay_thai'] ?? null,
                'can_nang' => $validated['can_nang'] ?? null,
                'huyet_ap_tam_thu' => $validated['huyet_ap_tam_thu'] ?? null,
                'huyet_ap_tam_truong' => $validated['huyet_ap_tam_truong'] ?? null,
                'nhiet_do' => $validated['nhiet_do'] ?? null,
                'nhip_tim_me' => $validated['nhip_tim_me'] ?? null,
                'chieu_cao_tu_cung' => $validated['chieu_cao_tu_cung'] ?? null,
                'vong_bung' => $validated['vong_bung'] ?? null,
                'nhip_tim_thai' => $validated['nhip_tim_thai'] ?? null,
                'vi_tri_thai' => $validated['vi_tri_thai'] ?? null,
                'trieu_chung' => $validated['trieu_chung'] ?? null,
                'kham_lam_sang' => $validated['kham_lam_sang'] ?? null,
                'chi_dinh' => $validated['chi_dinh'] ?? null,
                'danh_gia' => $validated['danh_gia'] ?? null,
                'tu_van' => $validated['tu_van'] ?? null,
                'hen_kham_lai' => $validated['hen_kham_lai'] ?? null,
                'ghi_chu' => $validated['ghi_chu'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('doctor.theo-doi-thai-ky.show', $theoDoiThaiKy->id)
                ->with('success', 'Đã thêm lịch khám thai thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Xem chi tiết lịch khám
     */
    public function show(LichKhamThai $lichKhamThai)
    {
        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi) {
            abort(403);
        }

        $lichKhamThai->load(['theoDoiThaiKy.user', 'bacSi']);

        return view('doctor.lich-kham-thai.show', compact('lichKhamThai'));
    }

    /**
     * Form cập nhật lịch khám
     */
    public function edit(LichKhamThai $lichKhamThai)
    {
        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi || $lichKhamThai->bac_si_id !== $bacSi->id) {
            abort(403);
        }

        return view('doctor.lich-kham-thai.edit', compact('lichKhamThai'));
    }

    /**
     * Cập nhật lịch khám
     */
    public function update(Request $request, LichKhamThai $lichKhamThai)
    {
        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi || $lichKhamThai->bac_si_id !== $bacSi->id) {
            return back()->with('error', 'Bạn không có quyền cập nhật lịch khám này.');
        }

        $validated = $request->validate([
            'ngay_kham' => 'required|date',
            'tuan_thai' => 'required|integer|min:0|max:42',
            'ngay_thai' => 'nullable|integer|min:0|max:6',
            'can_nang' => 'nullable|numeric|min:0',
            'huyet_ap_tam_thu' => 'nullable|numeric|min:0',
            'huyet_ap_tam_truong' => 'nullable|numeric|min:0',
            'nhiet_do' => 'nullable|numeric|min:0',
            'nhip_tim_me' => 'nullable|integer|min:0',
            'chieu_cao_tu_cung' => 'nullable|numeric|min:0',
            'vong_bung' => 'nullable|numeric|min:0',
            'nhip_tim_thai' => 'nullable|integer|min:0',
            'vi_tri_thai' => 'nullable|string|max:100',
            'trieu_chung' => 'nullable|string',
            'kham_lam_sang' => 'nullable|string',
            'chi_dinh' => 'nullable|string',
            'danh_gia' => 'nullable|string',
            'tu_van' => 'nullable|string',
            'hen_kham_lai' => 'nullable|date',
            'ghi_chu' => 'nullable|string',
        ]);

        try {
            $lichKhamThai->update($validated);

            return redirect()->route('doctor.theo-doi-thai-ky.show', $lichKhamThai->theo_doi_thai_ky_id)
                ->with('success', 'Đã cập nhật lịch khám thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Xóa lịch khám
     */
    public function destroy(LichKhamThai $lichKhamThai)
    {
        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi || $lichKhamThai->bac_si_id !== $bacSi->id) {
            return back()->with('error', 'Bạn không có quyền xóa lịch khám này.');
        }

        try {
            $theoDoiId = $lichKhamThai->theo_doi_thai_ky_id;
            $lichKhamThai->delete();

            return redirect()->route('doctor.theo-doi-thai-ky.show', $theoDoiId)
                ->with('success', 'Đã xóa lịch khám thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}

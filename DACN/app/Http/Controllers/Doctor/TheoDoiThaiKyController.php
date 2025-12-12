<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\BacSi;
use App\Models\TheoDoiThaiKy;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TheoDoiThaiKyController extends Controller
{
    /**
     * Danh sách theo dõi thai kỳ
     */
    public function index(Request $request)
    {
        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi) {
            abort(403);
        }

        $query = TheoDoiThaiKy::with(['user', 'bacSi'])
            ->where('bac_si_id', $bacSi->id);

        // Filter by status
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Search by patient name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('so_dien_thoai', 'like', "%{$search}%");
            });
        }

        $theoDoiList = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('doctor.theo-doi-thai-ky.index', compact('theoDoiList'));
    }

    /**
     * Form tạo mới hồ sơ theo dõi thai kỳ
     */
    public function create(Request $request)
    {
        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi) {
            abort(403);
        }

        // Nếu có user_id từ request (từ trang bệnh án)
        $user = null;
        if ($request->filled('user_id')) {
            $user = User::find($request->user_id);
        }

        return view('doctor.theo-doi-thai-ky.create', compact('user'));
    }

    /**
     * Lưu hồ sơ theo dõi thai kỳ
     */
    public function store(Request $request)
    {
        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi) {
            return back()->with('error', 'Không tìm thấy thông tin bác sĩ.');
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'ngay_kinh_cuoi' => 'required|date',
            'so_lan_mang_thai' => 'required|integer|min:1',
            'so_lan_sinh' => 'required|integer|min:0',
            'so_con_song' => 'required|integer|min:0',
            'loai_thai' => 'required|in:Đơn thai,Song thai,Đa thai',
            'nhom_mau' => 'nullable|string|max:10',
            'rh' => 'nullable|string|max:10',
            'can_nang_truoc_mang_thai' => 'nullable|numeric|min:0',
            'chieu_cao' => 'nullable|numeric|min:0',
            'tien_su_san_khoa' => 'nullable|string',
            'tien_su_benh_ly' => 'nullable|string',
            'di_ung' => 'nullable|string',
            'ghi_chu' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Tính BMI
            $bmi = null;
            if ($validated['can_nang_truoc_mang_thai'] && $validated['chieu_cao']) {
                $chieuCaoM = $validated['chieu_cao'] / 100;
                $bmi = $validated['can_nang_truoc_mang_thai'] / ($chieuCaoM * $chieuCaoM);
            }

            // Tính ngày dự sinh (LMP + 280 ngày)
            $ngayDuSinh = TheoDoiThaiKy::tinhNgayDuSinh($validated['ngay_kinh_cuoi']);

            $theoDoiThaiKy = TheoDoiThaiKy::create([
                'user_id' => $validated['user_id'],
                'bac_si_id' => $bacSi->id,
                'ngay_kinh_cuoi' => $validated['ngay_kinh_cuoi'],
                'ngay_du_sinh' => $ngayDuSinh,
                'so_lan_mang_thai' => $validated['so_lan_mang_thai'],
                'so_lan_sinh' => $validated['so_lan_sinh'],
                'so_con_song' => $validated['so_con_song'],
                'loai_thai' => $validated['loai_thai'],
                'nhom_mau' => $validated['nhom_mau'] ?? null,
                'rh' => $validated['rh'] ?? null,
                'can_nang_truoc_mang_thai' => $validated['can_nang_truoc_mang_thai'] ?? null,
                'chieu_cao' => $validated['chieu_cao'] ?? null,
                'bmi_truoc_mang_thai' => $bmi,
                'tien_su_san_khoa' => $validated['tien_su_san_khoa'] ?? null,
                'tien_su_benh_ly' => $validated['tien_su_benh_ly'] ?? null,
                'di_ung' => $validated['di_ung'] ?? null,
                'trang_thai' => 'Đang theo dõi',
                'ghi_chu' => $validated['ghi_chu'] ?? null,
            ]);

            // Tự động tạo lịch tiêm chủng khuyến cáo
            $this->taoLichTiemChungTuDong($theoDoiThaiKy);

            DB::commit();

            return redirect()->route('doctor.theo-doi-thai-ky.show', $theoDoiThaiKy->id)
                ->with('success', 'Đã tạo hồ sơ theo dõi thai kỳ thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Xem chi tiết hồ sơ theo dõi thai kỳ
     */
    public function show(TheoDoiThaiKy $theoDoiThaiKy)
    {
        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi) {
            abort(403);
        }

        $theoDoiThaiKy->load([
            'user',
            'bacSi',
            'lichKhamThai' => function ($q) {
                $q->orderBy('ngay_kham', 'desc');
            },
            'tiemChung' => function ($q) {
                $q->orderBy('ngay_du_kien', 'asc');
            },
            'theoDoiHauSan' => function ($q) {
                $q->orderBy('ngay_kham', 'desc');
            }
        ]);

        // Tính tuổi thai hiện tại
        $tuoiThai = $theoDoiThaiKy->tuoiThaiHienTai();
        $soNgayConLai = $theoDoiThaiKy->soNgayConLai();

        return view('doctor.theo-doi-thai-ky.show', compact('theoDoiThaiKy', 'tuoiThai', 'soNgayConLai'));
    }

    /**
     * Form cập nhật hồ sơ
     */
    public function edit(TheoDoiThaiKy $theoDoiThaiKy)
    {
        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi || $theoDoiThaiKy->bac_si_id !== $bacSi->id) {
            abort(403);
        }

        return view('doctor.theo-doi-thai-ky.edit', compact('theoDoiThaiKy'));
    }

    /**
     * Cập nhật hồ sơ
     */
    public function update(Request $request, TheoDoiThaiKy $theoDoiThaiKy)
    {
        $bacSi = BacSi::where('user_id', Auth::id())->first();
        if (!$bacSi || $theoDoiThaiKy->bac_si_id !== $bacSi->id) {
            return back()->with('error', 'Bạn không có quyền cập nhật hồ sơ này.');
        }

        $validated = $request->validate([
            'ngay_kinh_cuoi' => 'required|date',
            'loai_thai' => 'required|in:Đơn thai,Song thai,Đa thai',
            'nhom_mau' => 'nullable|string|max:10',
            'rh' => 'nullable|string|max:10',
            'can_nang_truoc_mang_thai' => 'nullable|numeric|min:0',
            'chieu_cao' => 'nullable|numeric|min:0',
            'tien_su_san_khoa' => 'nullable|string',
            'tien_su_benh_ly' => 'nullable|string',
            'di_ung' => 'nullable|string',
            'trang_thai' => 'required|in:Đang theo dõi,Đã sinh,Sẩy thai,Nạo thai,Chuyển viện',
            'ngay_ket_thuc' => 'nullable|date',
            'ket_qua_thai_ky' => 'nullable|string',
            'ghi_chu' => 'nullable|string',
        ]);

        try {
            // Tính BMI
            $bmi = null;
            if ($validated['can_nang_truoc_mang_thai'] && $validated['chieu_cao']) {
                $chieuCaoM = $validated['chieu_cao'] / 100;
                $bmi = $validated['can_nang_truoc_mang_thai'] / ($chieuCaoM * $chieuCaoM);
            }

            // Tính lại ngày dự sinh nếu thay đổi ngày kinh cuối
            $ngayDuSinh = TheoDoiThaiKy::tinhNgayDuSinh($validated['ngay_kinh_cuoi']);

            $theoDoiThaiKy->update([
                'ngay_kinh_cuoi' => $validated['ngay_kinh_cuoi'],
                'ngay_du_sinh' => $ngayDuSinh,
                'loai_thai' => $validated['loai_thai'],
                'nhom_mau' => $validated['nhom_mau'] ?? null,
                'rh' => $validated['rh'] ?? null,
                'can_nang_truoc_mang_thai' => $validated['can_nang_truoc_mang_thai'] ?? null,
                'chieu_cao' => $validated['chieu_cao'] ?? null,
                'bmi_truoc_mang_thai' => $bmi,
                'tien_su_san_khoa' => $validated['tien_su_san_khoa'] ?? null,
                'tien_su_benh_ly' => $validated['tien_su_benh_ly'] ?? null,
                'di_ung' => $validated['di_ung'] ?? null,
                'trang_thai' => $validated['trang_thai'],
                'ngay_ket_thuc' => $validated['ngay_ket_thuc'] ?? null,
                'ket_qua_thai_ky' => $validated['ket_qua_thai_ky'] ?? null,
                'ghi_chu' => $validated['ghi_chu'] ?? null,
            ]);

            return redirect()->route('doctor.theo-doi-thai-ky.show', $theoDoiThaiKy->id)
                ->with('success', 'Đã cập nhật hồ sơ thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Tạo lịch tiêm chủng tự động
     */
    private function taoLichTiemChungTuDong(TheoDoiThaiKy $theoDoiThaiKy)
    {
        $ngayKinhCuoi = Carbon::parse($theoDoiThaiKy->ngay_kinh_cuoi);
        $vaccines = \App\Models\TiemChungMeBau::danhSachVaccineKhuyenCao();

        foreach ($vaccines as $vaccine) {
            $ngayDuKien = $ngayKinhCuoi->copy()->addWeeks($vaccine['tuan_thai']);

            \App\Models\TiemChungMeBau::create([
                'theo_doi_thai_ky_id' => $theoDoiThaiKy->id,
                'bac_si_id' => $theoDoiThaiKy->bac_si_id,
                'ten_vaccine' => $vaccine['ten'],
                'tuan_thai_du_kien' => $vaccine['tuan_thai'],
                'ngay_du_kien' => $ngayDuKien,
                'tong_so_mui' => $vaccine['so_mui'],
                'mui_so' => 1,
                'trang_thai' => 'Chưa tiêm',
            ]);
        }
    }
}

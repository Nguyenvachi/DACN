<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BacSi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class BacSiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = BacSi::query();

        // Search keyword across name and specialty
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('ho_ten', 'like', "%{$keyword}%")
                    ->orWhere('chuyen_khoa', 'like', "%{$keyword}%");
            });
        }

        // Filter by chuyên khoa
        if ($request->filled('chuyen_khoa')) {
            $query->where('chuyen_khoa', $request->chuyen_khoa);
        }

        // Filter by trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        $bacSis = $query->latest()->paginate(10)->appends($request->query());

        // Provide distinct list of specialties from DB for the filter select
        $chuyenKhoaList = BacSi::select('chuyen_khoa')
            ->whereNotNull('chuyen_khoa')
            ->distinct()
            ->orderBy('chuyen_khoa')
            ->pluck('chuyen_khoa');

        $trangThaiOptions = ['Đang hoạt động', 'Ngừng hoạt động'];

        return view('admin.bacsi.index', compact('bacSis', 'chuyenKhoaList', 'trangThaiOptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $chuyenKhoas = \App\Models\ChuyenKhoa::orderBy('ten')->get();
        return view('admin.bacsi.create', compact('chuyenKhoas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ho_ten' => 'required|string|max:255',
            'email' => 'required|email|unique:bac_sis,email|unique:users,email',
            'chuyen_khoa_id' => 'required|exists:chuyen_khoas,id',
            'so_dien_thoai' => 'required|string|max:20',
            'avatar' => 'nullable|image|max:2048',
            'kinh_nghiem' => 'nullable|integer|min:0|max:50',
            'dia_chi' => 'nullable|string|max:500',
            'mo_ta' => 'nullable|string',
            'trang_thai' => 'required|in:Đang hoạt động,Ngừng hoạt động',
        ]);

        try {
            DB::beginTransaction();

            // Lấy tên chuyên khoa để lưu vào cột chuyen_khoa
            $chuyenKhoa = \App\Models\ChuyenKhoa::findOrFail($request->chuyen_khoa_id);

            // Tạo bác sĩ với đầy đủ thông tin (xử lý upload avatar nếu có)
            $bacSiData = [
                'ho_ten' => $request->ho_ten,
                'email' => $request->email,
                'chuyen_khoa' => $chuyenKhoa->ten,
                'so_dien_thoai' => $request->so_dien_thoai,
                'kinh_nghiem' => $request->kinh_nghiem ?? 0,
                'dia_chi' => $request->dia_chi,
                'mo_ta' => $request->mo_ta,
                'trang_thai' => $request->trang_thai,
            ];

            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store('avatars', 'public');
                $bacSiData['avatar'] = $path;
            }

            $bacSi = BacSi::create($bacSiData);

            // Tạo user account
            $password = 'Thanh@123';
            $user = User::create([
                'name' => $bacSi->ho_ten,
                'email' => $request->email,
                'password' => Hash::make($password),
                'role' => 'doctor',
            ]);

            // Liên kết bác sĩ với user
            $bacSi->user_id = $user->id;
            $bacSi->save();

            // Đồng bộ avatar sang user nếu có
            if (!empty($bacSi->avatar) && $user) {
                $user->forceFill(['avatar' => $bacSi->avatar])->save();
            }

            // Đồng bộ chuyên khoa (many-to-many) - chỉ 1 chuyên khoa
            $bacSi->chuyenKhoas()->sync([$request->chuyen_khoa_id]);

            DB::commit();

            session()->flash('status', "Đã tạo bác sĩ '{$bacSi->ho_ten}' thành công. Email: {$request->email}, Mật khẩu: {$password}");
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Lỗi khi tạo bác sĩ: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Đã có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('admin.bac-si.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(BacSi $bacSi)
    {
        return view('admin.bacsi.show', compact('bacSi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BacSi $bacSi)
    {
        $chuyenKhoas = \App\Models\ChuyenKhoa::orderBy('ten')->get();
        $selectedChuyenKhoaId = $bacSi->chuyenKhoas()->first()->id ?? null;
        return view('admin.bacsi.edit', compact('bacSi', 'chuyenKhoas', 'selectedChuyenKhoaId'));
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, BacSi $bacSi)
    {
        $request->validate([
            'ho_ten' => 'required|string|max:255',
            'email' => 'required|email|unique:bac_sis,email,' . $bacSi->id,
            'chuyen_khoa_id' => 'required|exists:chuyen_khoas,id',
            'so_dien_thoai' => 'required|string|max:20',
            'avatar' => 'nullable|image|max:2048',
            'kinh_nghiem' => 'nullable|integer|min:0|max:50',
            'dia_chi' => 'nullable|string|max:500',
            'mo_ta' => 'nullable|string',
            'trang_thai' => 'required|in:Đang hoạt động,Ngừng hoạt động',
        ]);

        try {
            DB::beginTransaction();

            // Lấy tên chuyên khoa
            $chuyenKhoa = \App\Models\ChuyenKhoa::findOrFail($request->chuyen_khoa_id);

            // Cập nhật thông tin bác sĩ (xử lý avatar)
            $bacSiData = [
                'ho_ten' => $request->ho_ten,
                'email' => $request->email,
                'chuyen_khoa' => $chuyenKhoa->ten,
                'so_dien_thoai' => $request->so_dien_thoai,
                'kinh_nghiem' => $request->kinh_nghiem ?? 0,
                'dia_chi' => $request->dia_chi,
                'mo_ta' => $request->mo_ta,
                'trang_thai' => $request->trang_thai,
            ];

            if ($request->hasFile('avatar')) {
                // Xóa file cũ nếu có
                if (!empty($bacSi->avatar)) {
                    try {
                        Storage::disk('public')->delete($bacSi->avatar);
                    } catch (\Exception $e) {
                        // ignore deletion errors
                    }
                }

                $path = $request->file('avatar')->store('avatars', 'public');
                $bacSiData['avatar'] = $path;
            }

            $bacSi->update($bacSiData);

            // Cập nhật thông tin user nếu có (đồng bộ avatar nếu cần)
            if ($bacSi->user) {
                $userData = [
                    'name' => $request->ho_ten,
                    'email' => $request->email,
                ];

                if (!empty($bacSi->avatar)) {
                    $userData['avatar'] = $bacSi->avatar;
                }

                $bacSi->user->update($userData);
            }

            // Đồng bộ chuyên khoa
            $bacSi->chuyenKhoas()->sync([$request->chuyen_khoa_id]);

            DB::commit();

            return redirect()->route('admin.bac-si.index')->with('status', 'Cập nhật bác sĩ thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Lỗi khi cập nhật bác sĩ: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Đã có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(BacSi $bacSi)
    {
        try {
            // Kiểm tra xem có lịch hẹn không
            if ($bacSi->lichHens()->count() > 0) {
                return redirect()->route('admin.bac-si.index')
                    ->with('error', 'Không thể xóa bác sĩ đã có lịch hẹn!');
            }

            // Xóa file avatar nếu có
            if (!empty($bacSi->avatar)) {
                try {
                    Storage::disk('public')->delete($bacSi->avatar);
                } catch (\Exception $e) {
                    // ignore
                }
            }

            // Xóa user liên quan nếu có (và xóa avatar user nếu khác)
            if ($bacSi->user) {
                if (!empty($bacSi->user->avatar) && $bacSi->user->avatar !== $bacSi->avatar) {
                    try {
                        Storage::disk('public')->delete($bacSi->user->avatar);
                    } catch (\Exception $e) {
                        // ignore
                    }
                }

                $bacSi->user->delete();
            }

            $bacSi->delete();

            return redirect()->route('admin.bac-si.index')->with('status', 'Xóa bác sĩ thành công!');
        } catch (\Exception $e) {
            logger()->error('Lỗi khi xóa bác sĩ: ' . $e->getMessage());
            return redirect()->route('admin.bac-si.index')->with('error', 'Không thể xóa bác sĩ này!');
        }
    }
}

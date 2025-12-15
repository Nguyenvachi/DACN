<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NhanVien;
use App\Models\User;
use App\Models\CaLamViecNhanVien;
use App\Models\NhanVienAudit;
use App\Services\ShiftService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;

class NhanVienController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', NhanVien::class);
        $items = NhanVien::latest()->paginate(15);

        // Lấy danh sách trạng thái hiện có trong DB (distinct)
        $statuses = NhanVien::select('trang_thai')
            ->distinct()
            ->pluck('trang_thai')
            ->filter()
            ->values()
            ->all();

        // Tạo map label đễ hiển thị - nếu bạn có bảng labels riêng, có thể thay thế ở đây
        $statusLabels = [];
        foreach ($statuses as $st) {
            $label = str_replace(['_', '-'], ' ', $st);
            $label = mb_convert_case($label, MB_CASE_TITLE, 'UTF-8');
            $statusLabels[$st] = $label;
        }

        // Đảm bảo các trạng thái chuẩn vẫn có mặt (nếu DB chưa có)
        $defaults = ['active' => 'Active', 'inactive' => 'Inactive', 'disabled' => 'Disabled', 'on_leave' => 'On Leave'];
        foreach ($defaults as $k => $v) {
            if (!array_key_exists($k, $statusLabels)) {
                $statusLabels[$k] = $v;
            }
        }

        return view('admin.nhanvien.index', compact('items', 'statusLabels'));
    }

    public function create()
    {
        $this->authorize('create', NhanVien::class);
        return view('admin.nhanvien.create');
    }

    public function store(Request $r)
    {
        $this->authorize('create', NhanVien::class);
        $data = $r->validate([
            'ho_ten' => 'required|string|max:120',
            'chuc_vu' => 'nullable|string|max:100',
            'so_dien_thoai' => 'nullable|string|max:30',
            'email_cong_viec' => 'required|email|max:150|unique:nhan_viens,email_cong_viec|unique:users,email',
            'ngay_sinh' => 'nullable|date',
            'gioi_tinh' => 'nullable|string|max:10',
            'avatar' => 'nullable|image|max:2048',
        ]);

        // Tự động tạo User cho nhân viên
        $randomPassword = Str::random(16);
        $user = User::create([
            'name' => $data['ho_ten'],
            'email' => $data['email_cong_viec'],
            'password' => Hash::make($randomPassword),
            'role' => 'staff',
        ]);

        // Gửi email đặt lại mật khẩu cho nhân viên
        Password::sendResetLink(['email' => $user->email]);

        if ($r->hasFile('avatar')) {
            $data['avatar_path'] = $r->file('avatar')->store('nv_avatar', 'public');
        }

        $data['user_id'] = $user->id;
        $nv = NhanVien::create($data);

        return redirect()->route('admin.nhanvien.show', $nv)
            ->with('status', 'Đã tạo nhân viên và gửi email đặt mật khẩu đến ' . $user->email);
    }

    public function show(NhanVien $nhanvien)
    {
        $this->authorize('view', $nhanvien);
        $nhanvien->load('caLamViecs');
        return view('admin.nhanvien.show', compact('nhanvien'));
    }

    public function edit(NhanVien $nhanvien)
    {
        $this->authorize('update', $nhanvien);
        $nhanvien->load('user');
        return view('admin.nhanvien.edit', compact('nhanvien'));
    }

    public function update(Request $r, NhanVien $nhanvien)
    {
        $this->authorize('update', $nhanvien);
        $data = $r->validate([
            'ho_ten' => 'required|string|max:120',
            'chuc_vu' => 'nullable|string|max:100',
            'so_dien_thoai' => 'nullable|string|max:30',
            'ngay_sinh' => 'nullable|date',
            'gioi_tinh' => 'nullable|string|max:10',
            'trang_thai' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|max:2048',
        ]);
        if ($r->hasFile('avatar')) {
            // Xóa file cũ nếu có
            if ($nhanvien->avatar_path && Storage::disk('public')->exists($nhanvien->avatar_path)) {
                Storage::disk('public')->delete($nhanvien->avatar_path);
            }
            $data['avatar_path'] = $r->file('avatar')->store('nv_avatar', 'public');
        }

        $nhanvien->fill($data)->save();

        // Cập nhật tên trong User nếu cần
        if ($nhanvien->user) {
            $nhanvien->user->update(['name' => $data['ho_ten']]);
        }

        return back()->with('status', 'Đã cập nhật');
    }

    public function destroy(NhanVien $nhanvien)
    {
        $this->authorize('delete', $nhanvien);

        try {
            // Xóa avatar của nhân viên (nếu có)
            if ($nhanvien->avatar_path) {
                try {
                    if (Storage::disk('public')->exists($nhanvien->avatar_path)) {
                        Storage::disk('public')->delete($nhanvien->avatar_path);
                    }
                } catch (\Exception $e) {
                    // ignore storage errors
                    logger()->warning('Không thể xóa avatar nhân viên: ' . $e->getMessage());
                }
            }

            // Xóa user liên quan (nếu có)
            if ($nhanvien->user) {
                // Xóa avatar của user nếu khác file nhân viên
                if (!empty($nhanvien->user->avatar) && $nhanvien->user->avatar !== $nhanvien->avatar_path) {
                    try {
                        if (Storage::disk('public')->exists($nhanvien->user->avatar)) {
                            Storage::disk('public')->delete($nhanvien->user->avatar);
                        }
                    } catch (\Exception $e) {
                        logger()->warning('Không thể xóa avatar user nhân viên: ' . $e->getMessage());
                    }
                }
                $nhanvien->user->delete();
            }

            // Xóa nhân viên (các ca làm việc có foreign key cascade sẽ tự xóa)
            $nhanvien->delete();

            return redirect()->route('admin.nhanvien.index')->with('status', 'Xóa nhân viên thành công!');
        } catch (\Exception $e) {
            logger()->error('Lỗi khi xóa nhân viên: ' . $e->getMessage());
            return redirect()->route('admin.nhanvien.index')->with('error', 'Không thể xóa nhân viên này!');
        }
    }

    /**
     * Cập nhật nhanh trạng thái nhân viên (dùng bởi select trong bảng danh sách)
     */
    public function updateStatus(Request $r, NhanVien $nhanvien)
    {
        $this->authorize('update', $nhanvien);

        $data = $r->validate([
            'trang_thai' => 'required|string|max:50',
        ]);

        $nhanvien->fill(['trang_thai' => $data['trang_thai']])->save();

        return back()->with('status', 'Đã cập nhật trạng thái');
    }

    public function addShift(Request $r, NhanVien $nhanvien)
    {
        $this->authorize('update', $nhanvien);
        $data = $r->validate([
            'ngay' => 'required|date',
            'bat_dau' => 'required|date_format:H:i',
            'ket_thuc' => 'required|date_format:H:i|after:bat_dau',
            'ghi_chu' => 'nullable|string|max:255',
        ]);

        // Kiểm tra xung đột ca
        $shiftService = new ShiftService();
        $conflictCheck = $shiftService->checkConflict(
            $nhanvien->id,
            $data['ngay'],
            $data['bat_dau'],
            $data['ket_thuc']
        );

        if ($conflictCheck['conflict']) {
            return back()->withErrors(['shift_conflict' => $conflictCheck['message']])->withInput();
        }

        $data['nhan_vien_id'] = $nhanvien->id;
        CaLamViecNhanVien::create($data);
        return back()->with('status', 'Đã thêm ca làm việc');
    }

    public function history(NhanVien $nhanvien)
    {
        $this->authorize('viewHistory', $nhanvien);
        $audits = NhanVienAudit::where('nhan_vien_id', $nhanvien->id)
            ->with('user')
            ->latest()
            ->paginate(20);
        return view('admin.nhanvien.history', compact('nhanvien', 'audits'));
    }

    public function exportShifts(Request $r)
    {
        $this->authorize('exportShifts', NhanVien::class);

        // Nếu không có tham số, hiển thị form
        if (!$r->has('start_date')) {
            $nhanViens = NhanVien::orderBy('ho_ten')->get();
            return view('admin.nhanvien.export_shifts', compact('nhanViens'));
        }

        $data = $r->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'nhan_vien_id' => 'nullable|exists:nhan_viens,id',
        ]);

        $query = CaLamViecNhanVien::with('nhanVien')
            ->whereBetween('ngay', [$data['start_date'], $data['end_date']]);

        if (!empty($data['nhan_vien_id'])) {
            $query->where('nhan_vien_id', $data['nhan_vien_id']);
        }

        $shifts = $query->orderBy('ngay')->orderBy('bat_dau')->get();

        $filename = 'ca-lam-viec-' . date('Y-m-d-His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($shifts) {
            $file = fopen('php://output', 'w');
            // UTF-8 BOM để Excel hiển thị đúng tiếng Việt
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header
            fputcsv($file, ['ID', 'Nhân viên', 'Ngày', 'Bắt đầu', 'Kết thúc', 'Ghi chú']);

            // Data
            foreach ($shifts as $shift) {
                fputcsv($file, [
                    $shift->id,
                    $shift->nhanVien->ho_ten ?? '',
                    $shift->ngay,
                    $shift->bat_dau,
                    $shift->ket_thuc,
                    $shift->ghi_chu ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

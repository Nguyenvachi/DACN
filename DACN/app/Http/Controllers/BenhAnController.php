<?php

namespace App\Http\Controllers;

use App\Models\BenhAn;
use App\Models\BenhAnFile;
use App\Models\BacSi;
use App\Models\LichHen;
use App\Models\User;
use App\Models\DonThuoc;
use App\Models\DonThuocItem;
use App\Models\Thuoc;
use App\Models\XetNghiem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BenhAnController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(BenhAn::class, 'benh_an');
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $query = BenhAn::with(['benhNhan', 'bacSi']);

        if ($user->role === 'doctor' && $user->bacSi) {
            $query->where('bac_si_id', $user->bacSi->id);
        } elseif ($user->role === 'patient') {
            $query->where('user_id', $user->id);
        }

        // THÊM: bộ lọc
        if ($request->filled('patient_id')) {
            $query->where('user_id', (int)$request->patient_id);
        }
        if ($request->filled('from')) {
            $query->whereDate('ngay_kham', '>=', $request->date('from'));
        }
        if ($request->filled('to')) {
            $query->whereDate('ngay_kham', '<=', $request->date('to'));
        }
        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('tieu_de', 'like', "%{$q}%")
                    ->orWhere('trieu_chung', 'like', "%{$q}%")
                    ->orWhere('chuan_doan', 'like', "%{$q}%")
                    ->orWhere('dieu_tri', 'like', "%{$q}%");
            });
        }

        $records = $query->orderByDesc('ngay_kham')->paginate(12)->withQueryString();

        return view('benh_an.index', compact('records'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', BenhAn::class);

        $patients = User::where('role', 'patient')
            ->select('id', 'name', 'email', 'so_dien_thoai', 'ngay_sinh', 'gioi_tinh')
            ->orderBy('name')
            ->get();

        $doctorId = optional($request->user()->bacSi)->id;

        // Lấy lịch hẹn với thông tin đầy đủ
        $appointments = LichHen::with('dichVu')
            ->when($doctorId, fn($q) => $q->where('bac_si_id', $doctorId))
            ->whereIn('trang_thai', ['Đã xác nhận', 'Đã check-in', 'Đang khám'])
            ->whereDate('ngay_hen', '>=', now()->subDays(7)) // Trong 7 ngày gần đây
            ->orderByDesc('ngay_hen')
            ->orderByDesc('thoi_gian_hen')
            ->limit(50)
            ->get();

        // Sử dụng view enhanced (đã kích hoạt)
        return view('benh_an.create', compact('patients', 'appointments', 'doctorId'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', BenhAn::class);

        $data = $request->validate([
            'user_id'     => ['required', 'exists:users,id'],
            'bac_si_id'   => ['required', 'exists:bac_sis,id'],
            'lich_hen_id' => ['nullable', 'exists:lich_hens,id'],
            'ngay_kham'   => ['required', 'date'],
            'tieu_de'     => ['required', 'string', 'max:255'],
            'trieu_chung' => ['nullable', 'string'],
            'chuan_doan'  => ['nullable', 'string'],
            'dieu_tri'    => ['nullable', 'string'],
            'ghi_chu'     => ['nullable', 'string'],
            'files.*'     => ['nullable', 'file', 'max:8192'],
        ]);

        $record = BenhAn::create($data);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                // BỔ SUNG: dùng benh_an_private disk thay vì public
                $path = $file->store('files', 'benh_an_private');
                BenhAnFile::create([
                    'benh_an_id' => $record->id,
                    'ten_file'   => $file->getClientOriginalName(),
                    'path'       => $path,
                    'loai_mime'  => $file->getClientMimeType(),
                    'size_bytes' => $file->getSize(),
                    'uploaded_by' => $request->user()->id,
                    'disk' => 'benh_an_private', // BỔ SUNG: lưu disk name
                ]);
            }
        }

        return redirect()->route($this->routeByRole('index'))->with('status', 'Đã tạo bệnh án.');
    }

    public function show(BenhAn $benh_an)
    {
        $benh_an->load(['benhNhan', 'bacSi', 'files', 'xetNghiems', 'donThuocs.items.thuoc']);
        $role = $this->getCurrentRole();
        return view('benh_an.show', [
            'record'  => $benh_an,
            'benhAn'  => $benh_an, // bổ sung alias để view cũ dùng $benhAn không lỗi
            'role'    => $role,
        ]);
    }

    public function edit(BenhAn $benh_an)
    {
        $role = $this->getCurrentRole();

        // Load relationships cần thiết
        $benh_an->load(['user', 'bacSi', 'lichHen.dichVu', 'files', 'xetNghiems', 'donThuocs.items.thuoc']);

        // Nếu là doctor, dùng view mới với enhanced features
        if ($role === 'doctor') {
            return view('doctor.benh-an.edit', ['record' => $benh_an]);
        }

        // Admin/Staff dùng view cũ
        $patients = User::where('role', 'patient')->orderBy('name')->get(['id', 'name']);
        $appointments = LichHen::orderByDesc('ngay_hen')->limit(50)->get(['id', 'ngay_hen', 'thoi_gian_hen']);
        return view('benh_an.edit', ['record' => $benh_an, 'patients' => $patients, 'appointments' => $appointments, 'role' => $role]);
    }

    public function update(Request $request, BenhAn $benh_an)
    {
        $data = $request->validate([
            'user_id'     => ['required', 'exists:users,id'],
            'bac_si_id'   => ['required', 'exists:bac_sis,id'],
            'lich_hen_id' => ['nullable', 'exists:lich_hens,id'],
            'ngay_kham'   => ['required', 'date'],
            'tieu_de'     => ['required', 'string', 'max:255'],
            'trieu_chung' => ['nullable', 'string'],
            'chuan_doan'  => ['nullable', 'string'],
            'dieu_tri'    => ['nullable', 'string'],
            'ghi_chu'     => ['nullable', 'string'],
            'files.*'     => ['nullable', 'file', 'max:8192'],
        ]);

        $benh_an->update($data);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                // BỔ SUNG: dùng benh_an_private disk
                $path = $file->store('files', 'benh_an_private');
                BenhAnFile::create([
                    'benh_an_id' => $benh_an->id,
                    'ten_file'   => $file->getClientOriginalName(),
                    'path'       => $path,
                    'loai_mime'  => $file->getClientMimeType(),
                    'size_bytes' => $file->getSize(),
                    'uploaded_by' => $request->user()->id,
                    'disk' => 'benh_an_private', // BỔ SUNG
                ]);
            }
        }

        return redirect()->route($this->routeByRole('show'), $benh_an)->with('status', 'Đã cập nhật.');
    }

    public function destroy(BenhAn $benh_an)
    {
        foreach ($benh_an->files as $f) {
            Storage::disk('public')->delete($f->path);
            $f->delete();
        }
        $benh_an->delete();
        return redirect()->route($this->routeByRole('index'))->with('status', 'Đã xóa.');
    }

    /**
     * Export bệnh án ra file PDF
     */
    public function exportPdf(BenhAn $benh_an)
    {
        // Check authorization
        $this->authorize('view', $benh_an);

        // Load relationships
        $benh_an->load(['benhNhan', 'bacSi.user', 'dichVu', 'donThuocs.items.thuoc', 'xetNghiems']);

        // Tạo PDF view
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('benh_an.pdf', compact('benh_an'));

        // Download PDF
        $filename = 'benh-an-' . $benh_an->id . '-' . date('Ymd') . '.pdf';
        return $pdf->download($filename);
    }

    // THÊM: upload thêm file vào bệnh án
    public function uploadFile(Request $request, BenhAn $benh_an)
    {
        $this->authorize('update', $benh_an);

        $request->validate([
            'files.*' => ['required', 'file', 'max:8192'],
        ]);

        $uploadedCount = 0;
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                // BỔ SUNG: dùng benh_an_private disk
                $path = $file->store('files', 'benh_an_private');
                BenhAnFile::create([
                    'benh_an_id' => $benh_an->id,
                    'ten_file'   => $file->getClientOriginalName(),
                    'path'       => $path,
                    'loai_mime'  => $file->getClientMimeType(),
                    'size_bytes' => $file->getSize(),
                    'uploaded_by' => $request->user()->id,
                    'disk' => 'benh_an_private', // BỔ SUNG
                ]);
                $uploadedCount++;
            }

            // BỔ SUNG: Ghi audit log cho upload file
            \App\Observers\BenhAnObserver::logCustomAction(
                $benh_an,
                'files_uploaded',
                "Upload {$uploadedCount} tệp đính kèm"
            );
        }

        return back()->with('status', 'Đã tải lên tệp.');
    }

    // THÊM: xóa 1 tệp đính kèm
    public function destroyFile(BenhAn $benh_an, BenhAnFile $file)
    {
        $this->authorize('update', $benh_an);
        if ($file->benh_an_id !== $benh_an->id) {
            abort(404);
        }

        $fileName = $file->ten_file;

        // BỔ SUNG: xóa file từ đúng disk
        Storage::disk($file->disk_name)->delete($file->path);
        $file->delete();

        // BỔ SUNG: Ghi audit log cho xóa file
        \App\Observers\BenhAnObserver::logCustomAction(
            $benh_an,
            'file_deleted',
            "Xóa tệp đính kèm: {$fileName}"
        );

        return back()->with('status', 'Đã xóa tệp đính kèm.');
    }

    // Form kê đơn (mẹ)
    public function createPrescription(BenhAn $benhAn)
    {
        $this->authorize('update', $benhAn);
        $thuocs = Thuoc::orderBy('ten')->get(['id', 'ten', 'don_vi', 'ham_luong']);

        // xác định role theo vai trò đăng nhập (tránh rơi vào route patient gây 403)
        $role = $this->getCurrentRole();

        return view(
            view()->exists('benh_an.don_thuoc_create') ? 'benh_an.don_thuoc_create' : 'benhan.don_thuoc_create',
            compact('benhAn', 'thuocs', 'role')
        );
    }

    // Lưu đơn thuốc (mẹ)
    public function storePrescription(Request $request, BenhAn $benhAn)
    {
        $this->authorize('update', $benhAn);

        $data = $request->validate([
            'ghi_chu' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.thuoc_id' => 'required|exists:thuocs,id',
            'items.*.so_luong' => 'required|integer|min:1',
            'items.*.lieu_dung' => 'nullable|string|max:255',
            'items.*.cach_dung' => 'nullable|string|max:255',
        ]);

        $don = DonThuoc::create([
            'benh_an_id'  => $benhAn->id,
            'user_id'     => $benhAn->user_id,     // theo chuẩn user_id
            'bac_si_id'   => $benhAn->bac_si_id,   // gắn bác sĩ đang phụ trách bệnh án
            'lich_hen_id' => $benhAn->lich_hen_id, // nếu có
            'ghi_chu'     => $data['ghi_chu'] ?? null,
        ]);

        foreach ($data['items'] as $row) {
            DonThuocItem::create([
                'don_thuoc_id' => $don->id,
                'thuoc_id'     => $row['thuoc_id'],
                'so_luong'     => $row['so_luong'],
                'lieu_dung'    => $row['lieu_dung'] ?? null,
                'cach_dung'    => $row['cach_dung'] ?? null,
            ]);
        }

        // BỔ SUNG: Ghi audit log cho hoạt động kê đơn
        \App\Observers\BenhAnObserver::logCustomAction(
            $benhAn,
            'prescription_created',
            "Kê đơn thuốc #{$don->id} với {$don->items->count()} loại thuốc"
        );

        // điều hướng theo vai trò đăng nhập để tránh 403
        return redirect()
            ->route($this->routeByRole('show'), $benhAn)
            ->with('status', 'Đã tạo đơn thuốc');
    }

    // Thêm dòng thuốc (mẹ)
    public function addPrescriptionItem(Request $request, DonThuoc $donThuoc)
    {
        $this->authorize('update', $donThuoc->benhAn);

        $row = $request->validate([
            'thuoc_id' => 'required|exists:thuocs,id',
            'so_luong' => 'required|integer|min:1',
            'lieu_dung' => 'nullable|string|max:255',
            'cach_dung' => 'nullable|string|max:255',
        ]);

        DonThuocItem::create([
            'don_thuoc_id' => $donThuoc->id,
            'thuoc_id'     => $row['thuoc_id'],
            'so_luong'     => $row['so_luong'],
            'lieu_dung'    => $row['lieu_dung'] ?? null,
            'cach_dung'    => $row['cach_dung'] ?? null,
        ]);

        return back()->with('status', 'Đã thêm thuốc vào đơn');
    }

    // Upload xét nghiệm (mẹ)
    public function uploadXetNghiem(Request $request, BenhAn $benhAn)
    {
        $this->authorize('update', $benhAn);

        $data = $request->validate([
            'loai' => 'required|string|max:100',
            'file' => 'required|file|max:10240',
            'mo_ta' => 'nullable|string|max:255',
        ]);

        // BỔ SUNG: lưu vào benh_an_private disk thay vì public
        $path = $request->file('file')->store('xet_nghiem', 'benh_an_private');

        XetNghiem::create([
            'benh_an_id' => $benhAn->id,
            'user_id'    => $benhAn->user_id,
            'bac_si_id'  => $benhAn->bac_si_id,
            'loai'       => $data['loai'],
            'file_path'  => $path,
            'disk'       => 'benh_an_private', // BỔ SUNG
            'mo_ta'      => $data['mo_ta'] ?? null,
        ]);

        // BỔ SUNG: Ghi audit log cho upload xét nghiệm
        \App\Observers\BenhAnObserver::logCustomAction(
            $benhAn,
            'test_uploaded',
            "Upload kết quả xét nghiệm: {$data['loai']}"
        );

        return back()->with('status', 'Đã tải lên kết quả xét nghiệm');
    }

    private function routeByRole(string $action): string
    {
        $role = auth()->user()->role;
        if ($role === 'admin')  return "admin.benhan.$action";
        if ($role === 'doctor') return "doctor.benhan.$action";
        return "patient.benhan.$action";
    }

    // THÊM METHOD NÀY
    private function getCurrentRole(): string
    {
        $role = auth()->user()->role;
        if ($role === 'admin')  return 'admin';
        if ($role === 'doctor') return 'doctor';
        return 'patient';
    }

    // THÊM: xem audit log bệnh án
    public function auditLog(BenhAn $benh_an)
    {
        $this->authorize('view', $benh_an);

        $audits = $benh_an->audits()->with('user')->paginate(20);
        $role = $this->getCurrentRole();

        return view('benh_an.audit', [
            'benhAn' => $benh_an,
            'audits' => $audits,
            'role' => $role,
        ]);
    }

    // THÊM: download file bệnh án (bảo mật với signed URL)
    public function downloadFile(BenhAnFile $file)
    {
        // Authorize: chỉ admin, bác sĩ phụ trách, hoặc bệnh nhân của bệnh án mới được download
        $benhAn = $file->benhAn;
        $this->authorize('view', $benhAn);

        if (!Storage::disk($file->disk_name)->exists($file->path)) {
            abort(404, 'File không tồn tại');
        }

        // Trả về file download từ storage
        $filePath = storage_path('app/' . ($file->disk_name === 'public' ? 'public/' : 'benh_an/') . $file->path);

        // Force download (bắt buộc tải về)
        return response()->download($filePath, $file->ten_file);
    }

    // THÊM: download file xét nghiệm (bảo mật với signed URL)
    public function downloadXetNghiem(XetNghiem $xetNghiem)
    {
        // Authorize: chỉ admin, bác sĩ phụ trách, hoặc bệnh nhân của bệnh án mới được download
        $benhAn = $xetNghiem->benhAn;
        $this->authorize('view', $benhAn);

        if (!Storage::disk($xetNghiem->disk_name)->exists($xetNghiem->file_path)) {
            abort(404, 'File không tồn tại');
        }

        // Trả về file từ storage
        $filePath = storage_path('app/' . ($xetNghiem->disk_name === 'public' ? 'public/' : 'benh_an/') . $xetNghiem->file_path);

        // Lấy tên file từ path
        $fileName = basename($xetNghiem->file_path);

        // Force download (bắt buộc tải về)
        return response()->download($filePath, $fileName);
    }
}

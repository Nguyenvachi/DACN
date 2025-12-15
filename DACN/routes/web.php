<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cache;
use App\Models\LichHen;
use App\Notifications\AppointmentReminder;
use Carbon\Carbon;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\BacSiController as AdminBacSiController;
use App\Http\Controllers\Doctor\CalendarController as DoctorCalendarController;
use App\Http\Controllers\Doctor\DashboardController as DoctorDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BacSiController;
use App\Http\Controllers\DichVuController;
use App\Http\Controllers\LichHenController;
use App\Http\Controllers\BenhAnController;
use App\Http\Controllers\Admin\HoaDonController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\NhanVienController;
use App\Http\Controllers\Patient\PaymentController as PatientPaymentController;
use App\Http\Controllers\PatientDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Route cho trang chủ công khai
Route::get('/', function () {
    // Nếu đã đăng nhập, redirect theo role
    if (auth()->check()) {
            /** @var \App\Models\User $user */
            $user = auth()->user();
            $role = strtolower($user->roleKey());
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'doctor' => redirect()->route('doctor.dashboard'),
            'patient' => redirect()->route('public.bacsi.index'),
            'staff' => redirect()->route('staff.dashboard'),
            default => redirect()->route('dashboard'),
        };
    }

    // Chưa đăng nhập, hiển thị homepage công khai
    return view('home');
})->name('homepage');

// Route /home cho user đã đăng nhập
Route::get('/home', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    /** @var \App\Models\User $user */
    $user = auth()->user();
    $role = strtolower($user->roleKey());

    return match ($role) {
        'admin' => redirect()->route('admin.dashboard'),
        'doctor' => redirect()->route('doctor.dashboard'),
        'patient' => redirect()->route('public.bacsi.index'),
        'staff' => redirect()->route('staff.dashboard'),
        default => redirect()->route('dashboard'),
    };
})->name('home');

// VNPay Routes
Route::post('/payment/vnpay-create', [PaymentController::class, 'createVnpayPayment'])->name('vnpay.create');
Route::get('/payment/vnpay-return', [PaymentController::class, 'vnpayReturn'])->name('vnpay.return');
Route::get('/payment/vnpay-ipn', [PaymentController::class, 'vnpayIpn'])->name('vnpay.ipn');

// MoMo Routes
Route::post('/payment/momo-create', [PaymentController::class, 'createMomoPayment'])->name('momo.create');
Route::get('/payment/momo-return', [PaymentController::class, 'momoReturn'])->name('momo.return');
Route::post('/payment/momo-ipn', [PaymentController::class, 'momoIpn'])->name('momo.ipn');

// Nhóm tất cả các route yêu cầu xác thực (đăng nhập)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/api/bac-si/{bacSi}/thoi-gian-trong/{ngay}', [LichHenController::class, 'getAvailableTimeSlots'])->name('api.bacsi.slots');
    Route::post('/api/slot-lock', [\App\Http\Controllers\Public\SlotLockController::class, 'lock'])->name('api.slot.lock');
    Route::post('/api/slot-unlock', [\App\Http\Controllers\Public\SlotLockController::class, 'unlock'])->name('api.slot.unlock');
    Route::post('/api/slot-check', [\App\Http\Controllers\Public\SlotLockController::class, 'check'])->name('api.slot.check');
    Route::post('/api/coupons/validate', [\App\Http\Controllers\Patient\CouponController::class, 'check'])->name('api.coupons.validate');

    // Dashboard - Tự động chuyển theo role
    Route::get('/dashboard', function () {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $role = strtolower($user->roleKey());

        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'doctor' => redirect()->route('doctor.dashboard'),
            'patient' => redirect()->route('patient.dashboard'),
            'staff' => redirect()->route('staff.dashboard'),
            default => view('dashboard'),
        };
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Profile routes cho các roles khác nhau (File mẹ: routes/web.php)
    Route::put('/profile/doctor', [ProfileController::class, 'updateDoctor'])->name('profile.updateDoctor');
    Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.uploadAvatar');
    Route::post('/profile/medical', [ProfileController::class, 'updateMedical'])->name('profile.updateMedical');
    Route::post('/profile/notifications', [ProfileController::class, 'updateNotifications'])->name('profile.updateNotifications');

    Route::get('/lich-hen-cua-toi', [LichHenController::class, 'myAppointments'])->name('lichhen.my');
    Route::get('/danh-sach-bac-si', [BacSiController::class, 'publicIndex'])->name('public.bacsi.index');
    Route::get('/danh-sach-dich-vu', [DichVuController::class, 'publicIndex'])->name('public.dichvu.index');
    Route::get('/dat-lich/{bacSi}', [LichHenController::class, 'create'])->name('lichhen.create');
    Route::post('/luu-lich-hen', [LichHenController::class, 'store'])->name('lichhen.store');
    Route::get('/dat-lich-thanh-cong', function () { return view('public.lichhen.success'); })->name('lichhen.thanhcong');

    // Patient payment page (choose payment method) - used by invoice view
    Route::get('/payment/{lichHen}', [\App\Http\Controllers\Patient\PaymentController::class, 'show'])->name('patient.payment');
    Route::post('/payment/{lichHen}/skip', [\App\Http\Controllers\Patient\PaymentController::class, 'skip'])->name('patient.payment.skip');

    // Lịch rảnh bác sĩ theo tuần
    Route::get('/bac-si/{bacSi}/lich-ranh', [App\Http\Controllers\Public\BacSiScheduleController::class, 'weeklySchedule'])->name('public.bacsi.schedule');

    // API: Lấy N slot kế tiếp
    Route::get('/api/bac-si/{bacSi}/next-slots', [App\Http\Controllers\Public\BacSiScheduleController::class, 'nextAvailableSlots'])->name('api.bacsi.next-slots');
});

// =========================================================================
// KHU VỰC ADMIN PANEL - ĐÃ BỔ SUNG KHÓA CHO TỪNG PHÒNG
// =========================================================================
Route::middleware(['auth', 'permission:view-dashboard'])->prefix('admin')->name('admin.')->group(function () {

    // 1. Dashboard & Báo cáo (Ai có vé vào cổng đều xem được cái này)
    Route::get('/dashboard', [ReportController::class, 'dashboard'])->name('dashboard');
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/appointments/status', [ReportController::class, 'appointmentsStatus'])->name('appointments.status');
        Route::get('/appointments/daily', [ReportController::class, 'appointmentsDaily'])->name('appointments.daily');
        Route::get('/revenue/service', [ReportController::class, 'revenueByService'])->name('revenue.service');
        Route::get('/revenue/doctor', [ReportController::class, 'revenueByDoctor'])->name('revenue.doctor');
        Route::get('/revenue/gateway', [ReportController::class, 'revenueByGateway'])->name('revenue.gateway');

        // MỞ RỘNG: Các endpoints mới (Parent file: routes/web.php)
        Route::get('/new-patients-monthly', [ReportController::class, 'newPatientsMonthly'])->name('new_patients_monthly');
        Route::get('/top-services', [ReportController::class, 'topServices'])->name('top_services');
        Route::get('/refunds', [ReportController::class, 'refundStatistics'])->name('refunds');
        Route::get('/medicine-sales', [ReportController::class, 'medicineSalesStatistics'])->name('medicine_sales');
        Route::get('/compare-previous', [ReportController::class, 'compareWithPrevious'])->name('compare_previous');

        // Export CSV (Parent file: routes/web.php)
        Route::get('/export-csv', [ReportController::class, 'exportCsv'])->name('export_csv');
    });

    // 2. NHÓM QUẢN LÝ BÁC SĨ (Cần quyền view-doctors)
    Route::middleware(['permission:view-doctors'])->group(function () {
        Route::resource('bac-si', AdminBacSiController::class);
        // Lịch làm việc/nghỉ cũng thuộc về quản lý bác sĩ
        Route::get('/lich-lam-viec/{bacSi}', [App\Http\Controllers\Admin\LichLamViecController::class, 'index'])->name('lichlamviec.index');
        Route::post('/lich-lam-viec/{bacSi}', [App\Http\Controllers\Admin\LichLamViecController::class, 'store'])->name('lichlamviec.store');
        Route::delete('/lich-lam-viec/{bacSi}/{lichLamViec}', [App\Http\Controllers\Admin\LichLamViecController::class, 'destroy'])->name('lichlamviec.destroy');
        Route::get('/lich-lam-viec/{bacSi}/export', [App\Http\Controllers\Admin\LichLamViecController::class, 'exportReport'])->name('lichlamviec.export');
        Route::get('/lich-nghi/{bacSi}', [App\Http\Controllers\Admin\LichNghiController::class, 'index'])->name('lichnghi.index');
        Route::post('/lich-nghi/{bacSi}', [App\Http\Controllers\Admin\LichNghiController::class, 'store'])->name('lichnghi.store');
        Route::delete('/lich-nghi/{lichNghi}', [App\Http\Controllers\Admin\LichNghiController::class, 'destroy'])->name('lichnghi.destroy');
        Route::get('/ca-dieu-chinh/{bacSi}', [App\Http\Controllers\Admin\CaDieuChinhController::class, 'index'])->name('cadieuchinh.index');
        Route::post('/ca-dieu-chinh/{bacSi}', [App\Http\Controllers\Admin\CaDieuChinhController::class, 'store'])->name('cadieuchinh.store');
        Route::put('/ca-dieu-chinh/{caDieuChinh}', [App\Http\Controllers\Admin\CaDieuChinhController::class, 'update'])->name('cadieuchinh.update');
        Route::delete('/ca-dieu-chinh/{caDieuChinh}', [App\Http\Controllers\Admin\CaDieuChinhController::class, 'destroy'])->name('cadieuchinh.destroy');
    });

    // 3. NHÓM DỊCH VỤ & CHUYÊN KHOA (Cần quyền view-services)
    Route::middleware(['permission:view-services'])->group(function () {
        Route::resource('dich-vu', DichVuController::class)->parameters(['dich-vu' => 'dichVu']);
        Route::resource('chuyen-khoa', \App\Http\Controllers\Admin\ChuyenKhoaController::class)->names([
            'index' => 'chuyenkhoa.index', 'create' => 'chuyenkhoa.create', 'store' => 'chuyenkhoa.store', 'show' => 'chuyenkhoa.show', 'edit' => 'chuyenkhoa.edit', 'update' => 'chuyenkhoa.update', 'destroy' => 'chuyenkhoa.destroy',
        ])->parameters(['chuyen-khoa' => 'chuyenkhoa']);

        // MỞ RỘNG: Routes phòng (Parent file: routes/web.php)
        Route::resource('phong', \App\Http\Controllers\Admin\PhongController::class)->names([
            'index' => 'phong.index', 'create' => 'phong.create', 'store' => 'phong.store', 'show' => 'phong.show', 'edit' => 'phong.edit', 'update' => 'phong.update', 'destroy' => 'phong.destroy',
        ]);

        // Routes mới cho phòng (Parent file: routes/web.php)
        Route::get('phong-diagram', [\App\Http\Controllers\Admin\PhongController::class, 'diagram'])->name('phong.diagram');
        Route::post('phong-check-conflict', [\App\Http\Controllers\Admin\PhongController::class, 'checkConflict'])->name('phong.check_conflict');
        Route::get('phong/{phong}/status', [\App\Http\Controllers\Admin\PhongController::class, 'getStatus'])->name('phong.get_status');
        Route::get('phong-available', [\App\Http\Controllers\Admin\PhongController::class, 'available'])->name('phong.available');
        Route::patch('phong/{phong}/update-status', [\App\Http\Controllers\Admin\PhongController::class, 'updateStatus'])->name('phong.update_status');
        Route::get('phong/{phong}/statistics', [\App\Http\Controllers\Admin\PhongController::class, 'statistics'])->name('phong.statistics');
        Route::post('phong-suggest', [\App\Http\Controllers\Admin\PhongController::class, 'suggestForDoctor'])->name('phong.suggest');
    });

    // 4. NHÓM QUẢN LÝ THUỐC (Cần quyền view-medicines)
    // Lưu ý: Route 'thuoc' (danh mục) khác với 'kho' (nhập xuất)
    Route::middleware(['permission:view-medicines'])->group(function () {
        Route::resource('thuoc', \App\Http\Controllers\Admin\ThuocController::class)->names([
            'index' => 'thuoc.index', 'create' => 'thuoc.create', 'store' => 'thuoc.store', 'show' => 'thuoc.show', 'edit' => 'thuoc.edit', 'update' => 'thuoc.update', 'destroy' => 'thuoc.destroy',
        ]);
    });

    // 4.1. NHÓM QUẢN LÝ MÃ GIẢM GIÁ (Cần quyền view-medicines - quản lý cửa hàng thuốc)
    Route::middleware(['permission:view-medicines'])->group(function () {
        Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class)->names([
            'index' => 'coupons.index', 'create' => 'coupons.create', 'store' => 'coupons.store', 'show' => 'coupons.show', 'edit' => 'coupons.edit', 'update' => 'coupons.update', 'destroy' => 'coupons.destroy',
        ]);
    });

    // 5. NHÓM QUẢN LÝ LỊCH HẸN & BỆNH ÁN (Cần quyền view-appointments hoặc view-medical-records)
    // Tạm thời dùng view-invoices cho Lễ tân xem lịch hẹn nếu cần, hoặc để admin
    Route::middleware(['permission:view-appointments'])->group(function(){
        Route::get('/lich-hen', [LichHenController::class, 'adminIndex'])->name('lichhen.index');
        Route::patch('/lich-hen/{lichHen}/status', [LichHenController::class, 'updateStatus'])->name('lichhen.updateStatus');
        Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
        Route::get('/calendar/events', [CalendarController::class, 'events'])->name('calendar.events');
    });

    // 5.1. QUẢN LÝ ĐÁNH GIÁ (Admin)
    Route::middleware(['permission:view-appointments'])->prefix('danhgia')->name('danhgia.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DanhGiaController::class, 'index'])->name('index');
        Route::get('/{danhGia}', [\App\Http\Controllers\Admin\DanhGiaController::class, 'show'])->name('show');
        Route::patch('/{danhGia}/approve', [\App\Http\Controllers\Admin\DanhGiaController::class, 'approve'])->name('approve');
        Route::patch('/{danhGia}/reject', [\App\Http\Controllers\Admin\DanhGiaController::class, 'reject'])->name('reject');
        Route::delete('/{danhGia}', [\App\Http\Controllers\Admin\DanhGiaController::class, 'destroy'])->name('destroy');
    });

    // 5.2. QUẢN LÝ CHAT (Admin)
    Route::middleware(['permission:view-appointments'])->prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ChatController::class, 'index'])->name('index');
        Route::get('/{conversation}', [\App\Http\Controllers\Admin\ChatController::class, 'show'])->name('show');
        Route::get('/{conversation}/messages', [\App\Http\Controllers\Admin\ChatController::class, 'getMessages'])->name('messages');
        Route::patch('/{conversation}/status', [\App\Http\Controllers\Admin\ChatController::class, 'updateStatus'])->name('update-status');
        Route::delete('/{conversation}', [\App\Http\Controllers\Admin\ChatController::class, 'destroy'])->name('destroy');
    });

    Route::middleware(['permission:view-medical-records'])->group(function(){
        Route::resource('benh-an', BenhAnController::class)->names([
            'index' => 'benhan.index', 'create' => 'benhan.create', 'store' => 'benhan.store', 'show' => 'benhan.show', 'edit' => 'benhan.edit', 'update' => 'benhan.update', 'destroy' => 'benhan.destroy',
        ]);
        // Expose export-pdf for admin area so view can call route('admin.benhan.exportPdf')
        Route::get('benh-an/{benh_an}/export-pdf', [BenhAnController::class, 'exportPdf'])->name('benhan.exportPdf');
        Route::post('benh-an/{benh_an}/files', [BenhAnController::class, 'uploadFile'])->name('benhan.files.upload');
        Route::delete('benh-an/{benh_an}/files/{file}', [BenhAnController::class, 'destroyFile'])->name('benhan.files.destroy');
        Route::get('benh-an/{benh_an}/audit', [BenhAnController::class, 'auditLog'])->name('benhan.audit');
        Route::get('benh-an/file/{file}/download', [BenhAnController::class, 'downloadFile'])->name('benhan.files.download')->middleware('signed');
        Route::get('benh-an/xet-nghiem/{xetNghiem}/download', [BenhAnController::class, 'downloadXetNghiem'])->name('benhan.xetnghiem.download')->middleware('signed');
    });

    // 6. NHÓM HÓA ĐƠN (Cần quyền view-invoices)
    Route::middleware(['permission:view-invoices'])->group(function(){
        Route::get('/hoa-don', [HoaDonController::class, 'index'])->name('hoadon.index');
        Route::get('/hoa-don/{hoaDon}', [HoaDonController::class, 'show'])->name('hoadon.show');
        Route::post('/lich-hen/{lichHen}/hoa-don', [HoaDonController::class, 'createFromAppointment'])->name('hoadon.create_from_appointment');
        Route::post('/hoa-don/{hoaDon}/thanh-toan/cash', [HoaDonController::class, 'cashCollect'])->name('hoadon.cash_collect');
        Route::get('/hoa-don/{hoaDon}/receipt', [ReceiptController::class, 'download'])->name('hoadon.receipt');
        // Thêm route download theo loại (phieu-thu, dich-vu, thuoc, tong-hop)
        // Parent file: `routes/web.php` (thêm route)
        Route::get('/hoa-don/{hoaDon}/receipt/{type}', [ReceiptController::class, 'downloadByType'])->name('hoadon.receipt.type');
        Route::get('/hoa-don/{hoaDon}/payment-logs', [HoaDonController::class, 'paymentLogs'])->name('hoadon.payment_logs');

        // Routes hoàn tiền (cần permission quản lý hóa đơn)
        Route::get('/hoa-don/{hoaDon}/refund', [HoaDonController::class, 'showRefundForm'])->name('hoadon.refund.form');
        Route::post('/hoa-don/{hoaDon}/refund', [HoaDonController::class, 'refund'])->name('hoadon.refund.process');
        Route::get('/hoa-don/{hoaDon}/refunds', [HoaDonController::class, 'refundsList'])->name('hoadon.refunds.list');
    });

    // 7. NHÓM KHO THUỐC & NCC (Cần quyền view-medicines)
    Route::middleware(['permission:view-medicines'])->prefix('kho')->name('kho.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\KhoThuocController::class, 'index'])->name('index');
        Route::get('/bao-cao', [\App\Http\Controllers\Admin\KhoThuocController::class, 'baoCao'])->name('bao_cao');
        Route::get('/thuoc/{thuoc}/lots', [\App\Http\Controllers\Admin\KhoThuocController::class, 'lots'])->name('lots');

        // API: Load thuốc theo NCC
        Route::get('/api/thuocs-by-ncc', [\App\Http\Controllers\Admin\KhoThuocController::class, 'getThuocsByNCC'])->name('api.thuocs_by_ncc');

        Route::middleware('can:kho-nhap')->group(function(){
            Route::get('/nhap', [\App\Http\Controllers\Admin\KhoThuocController::class, 'nhapForm'])->name('nhap.form');
            Route::post('/nhap', [\App\Http\Controllers\Admin\KhoThuocController::class, 'nhapStore'])->name('nhap.store');
        });
        Route::middleware('can:kho-xuat')->group(function(){
            Route::get('/xuat', [\App\Http\Controllers\Admin\KhoThuocController::class, 'xuatForm'])->name('xuat.form');
            Route::post('/xuat', [\App\Http\Controllers\Admin\KhoThuocController::class, 'xuatStore'])->name('xuat.store');
        });
    });
    Route::middleware(['permission:view-medicines'])->prefix('ncc')->name('ncc.')->group(function(){
        Route::get('/', [\App\Http\Controllers\Admin\NhaCungCapController::class,'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\NhaCungCapController::class,'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\NhaCungCapController::class,'store'])->name('store');
        Route::get('/{ncc}/edit', [\App\Http\Controllers\Admin\NhaCungCapController::class,'edit'])->name('edit');
        Route::put('/{ncc}', [\App\Http\Controllers\Admin\NhaCungCapController::class,'update'])->name('update');
        Route::delete('/{ncc}', [\App\Http\Controllers\Admin\NhaCungCapController::class,'destroy'])->name('destroy');

        // Quản lý thuốc của NCC
        Route::get('/{ncc}/thuocs', [\App\Http\Controllers\Admin\NhaCungCapController::class,'thuocs'])->name('thuocs');
        Route::post('/{ncc}/thuocs', [\App\Http\Controllers\Admin\NhaCungCapController::class,'addThuoc'])->name('thuocs.add');
        Route::put('/{ncc}/thuocs/{thuoc}', [\App\Http\Controllers\Admin\NhaCungCapController::class,'updateGiaThuoc'])->name('thuocs.update');
        Route::delete('/{ncc}/thuocs/{thuoc}', [\App\Http\Controllers\Admin\NhaCungCapController::class,'removeThuoc'])->name('thuocs.remove');
    });

    // 8. NHÓM NHÂN VIÊN & USER & PHÂN QUYỀN (Cần quyền view-users HOẶC role:admin cho chắc)
    // Ở đây mình dùng permission:view-users
    Route::middleware(['permission:view-users'])->group(function(){
        Route::resource('nhanvien', NhanVienController::class);
        // Cập nhật trạng thái nhanh cho nhân viên
        Route::patch('nhanvien/{nhanvien}/status', [\App\Http\Controllers\Admin\NhanVienController::class, 'updateStatus'])->name('nhanvien.updateStatus');
        Route::post('nhanvien/{nhanvien}/shift', [NhanVienController::class, 'addShift'])->name('nhanvien.shift.add');
        Route::get('nhanvien/{nhanvien}/history', [NhanVienController::class, 'history'])->name('nhanvien.history');
        Route::get('nhanvien-export-shifts', [NhanVienController::class, 'exportShifts'])->name('nhanvien.shifts.export');

        // Phân quyền
        Route::resource('permissions', \App\Http\Controllers\Admin\PermissionController::class)->except(['show', 'edit', 'update']);
        Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class)->except(['show']);
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('index');
            Route::get('/{user}/edit', [\App\Http\Controllers\Admin\UserManagementController::class, 'edit'])->name('edit');
            Route::put('/{user}', [\App\Http\Controllers\Admin\UserManagementController::class, 'update'])->name('update');
            Route::post('/{user}/lock', [\App\Http\Controllers\Admin\UserManagementController::class, 'lock'])->name('lock');
            Route::post('/{user}/unlock', [\App\Http\Controllers\Admin\UserManagementController::class, 'unlock'])->name('unlock');
            Route::post('/{user}/force-password-change', [\App\Http\Controllers\Admin\UserManagementController::class, 'forcePasswordChange'])->name('force-password-change');
        });
    });

    // 9. NHÓM CMS BÀI VIẾT (Cần permission 'view-posts' hoặc role 'admin')
    // Sử dụng middleware 'role_or_permission' để chấp nhận role OR permission
    Route::middleware(['role_or_permission:admin|view-posts'])->group(function () {
        Route::resource('baiviet', \App\Http\Controllers\Admin\BaiVietController::class)->names([
            'index' => 'baiviet.index', 'create' => 'baiviet.create', 'store' => 'baiviet.store', 'show' => 'baiviet.show', 'edit' => 'baiviet.edit', 'update' => 'baiviet.update', 'destroy' => 'baiviet.destroy',
        ]);
        Route::resource('danhmuc', \App\Http\Controllers\Admin\DanhMucController::class)->names([
            'index' => 'danhmuc.index', 'create' => 'danhmuc.create', 'store' => 'danhmuc.store', 'show' => 'danhmuc.show', 'edit' => 'danhmuc.edit', 'update' => 'danhmuc.update', 'destroy' => 'danhmuc.destroy',
        ]);
        Route::resource('tag', \App\Http\Controllers\Admin\TagController::class);

        // Media Library
        Route::get('media', [\App\Http\Controllers\Admin\MediaController::class, 'index'])->name('media.index');
        Route::post('media/upload', [\App\Http\Controllers\Admin\MediaController::class, 'upload'])->name('media.upload');
        Route::delete('media/destroy', [\App\Http\Controllers\Admin\MediaController::class, 'destroy'])->name('media.destroy');

        // Soft delete routes
        Route::get('baiviet-trashed', [\App\Http\Controllers\Admin\BaiVietController::class, 'trashed'])->name('baiviet.trashed');
        Route::post('baiviet/{id}/restore', [\App\Http\Controllers\Admin\BaiVietController::class, 'restore'])->name('baiviet.restore');
        Route::delete('baiviet/{id}/force-delete', [\App\Http\Controllers\Admin\BaiVietController::class, 'forceDelete'])->name('baiviet.forceDelete');
    });

    // Tools
    Route::post('/tools/reminders/tomorrow', [\App\Http\Controllers\Admin\ReminderController::class, 'sendTomorrow'])->name('tools.reminders.tomorrow');
    Route::post('/tools/reminders/next-3-hours', [\App\Http\Controllers\Admin\ReminderController::class, 'sendNext3Hours'])->name('tools.reminders.next3h');
    Route::get('/tools/test-mail', [\App\Http\Controllers\Admin\TestMailController::class, 'index'])->name('tools.test-mail');
    Route::post('/tools/test-mail/{id}', [\App\Http\Controllers\Admin\TestMailController::class, 'send'])->name('tools.test-mail.send');

    // API Calendar
    Route::prefix('calendar/api')->group(function () {
        Route::get('events', [CalendarController::class, 'apiEvents'])->name('calendar.api.events.role');
        Route::get('stats', [CalendarController::class, 'apiStats'])->name('calendar.api.stats.role');
        // tên route sửa thành 'calendar.api.drag_update' (nhóm 'admin.' ở trên sẽ tạo tên hoàn chỉnh 'admin.calendar.api.drag_update')
        Route::post('drag-update', [CalendarController::class, 'apiDragUpdate'])->name('calendar.api.drag_update');
        Route::post('resize-update', [\App\Http\Controllers\Admin\CalendarController::class, 'apiDragUpdate'])->name('calendar.api.resize_update');
        Route::get('events2', [CalendarController::class, 'apiEventsV2'])->name('calendar.api.events2');
        Route::get('stats2', [CalendarController::class, 'apiStatsV2'])->name('calendar.api.stats2');
    });
});

// // Staff Dashboard
// Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
//     Route::get('/dashboard', [\App\Http\Controllers\Staff\DashboardController::class, 'index'])->name('dashboard');
// });

// Nhóm role STAFF (Dashboard riêng)
// Sửa thành check quyền: Ai có vé view-dashboard thì được vào Dashboard nhân viên
// ENHANCED: Added check-in and queue management routes (Parent: routes/web.php)
Route::middleware(['auth', 'permission:view-dashboard'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Staff\DashboardController::class, 'index'])->name('dashboard');

    // Check-in Management (Parent: routes/web.php)
    Route::get('/checkin', [\App\Http\Controllers\Staff\CheckInController::class, 'index'])->name('checkin.index');
    Route::post('/checkin/checkin/{lichhen}', [\App\Http\Controllers\Staff\CheckInController::class, 'checkIn'])->name('checkin.checkin');
    Route::get('/checkin/quick-search', [\App\Http\Controllers\Staff\CheckInController::class, 'quickSearch'])->name('checkin.quick_search');
    Route::post('/checkin/bulk', [\App\Http\Controllers\Staff\CheckInController::class, 'bulkCheckIn'])->name('checkin.bulk');

    // Queue Management (Parent: routes/web.php)
    Route::get('/queue', [\App\Http\Controllers\Staff\QueueController::class, 'index'])->name('queue.index');
    Route::post('/queue/call-next/{lichhen}', [\App\Http\Controllers\Staff\QueueController::class, 'callNext'])->name('queue.call_next');
    Route::get('/queue/realtime-data', [\App\Http\Controllers\Staff\QueueController::class, 'realtimeData'])->name('queue.realtime');
});

// Doctor
Route::middleware(['auth', 'role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    // Dashboard cho Doctor (File mẹ: routes/web.php)
    Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');

    // Calendar - Lịch làm việc (File mẹ: routes/web.php)
    Route::get('/calendar', [DoctorCalendarController::class, 'index'])->name('calendar.index');
    Route::get('/api/calendar/events', [DoctorCalendarController::class, 'events'])->name('calendar.events');

    // Bệnh án (File mẹ: routes/web.php)
    Route::resource('benh-an', BenhAnController::class)->names([
        'index' => 'benhan.index', 'create' => 'benhan.create', 'store' => 'benhan.store', 'show' => 'benhan.show', 'edit' => 'benhan.edit', 'update' => 'benhan.update', 'destroy' => 'benhan.destroy',
    ]);
    Route::post('benh-an/{benh_an}/files', [BenhAnController::class, 'uploadFile'])->name('benhan.files.upload');
    Route::delete('benh-an/{benh_an}/files/{file}', [BenhAnController::class, 'destroyFile'])->name('benhan.files.destroy');
    Route::get('benh-an/{benh_an}/audit', [BenhAnController::class, 'auditLog'])->name('benhan.audit');
    Route::get('benh-an/{benh_an}/export-pdf', [BenhAnController::class, 'exportPdf'])->name('benhan.exportPdf');
    Route::get('benh-an/file/{file}/download', [BenhAnController::class, 'downloadFile'])->name('benhan.files.download')->middleware('signed');
    Route::get('benh-an/xet-nghiem/{xetNghiem}/download', [BenhAnController::class, 'downloadXetNghiem'])->name('benhan.xetnghiem.download')->middleware('signed');

    // Doctor Chat Routes (File mẹ: routes/web.php)
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Doctor\ChatController::class, 'index'])->name('index');
        Route::get('/{conversation}', [\App\Http\Controllers\Doctor\ChatController::class, 'show'])->name('show');
        Route::post('/{conversation}/send', [\App\Http\Controllers\Doctor\ChatController::class, 'sendMessage'])->name('send');
        Route::get('/{conversation}/messages', [\App\Http\Controllers\Doctor\ChatController::class, 'getMessages'])->name('messages');
    });

    // Lịch hẹn - Appointment Management (File mẹ: routes/web.php)
    Route::prefix('lich-hen')->name('lichhen.')->group(function () {
        Route::get('/pending', [\App\Http\Controllers\Doctor\LichHenController::class, 'pending'])->name('pending');
        Route::post('/{lichHen}/confirm', [\App\Http\Controllers\Doctor\LichHenController::class, 'confirm'])->name('confirm');
        Route::post('/{lichHen}/reject', [\App\Http\Controllers\Doctor\LichHenController::class, 'reject'])->name('reject');
        Route::post('/{lichHen}/complete', [\App\Http\Controllers\Doctor\LichHenController::class, 'complete'])->name('complete');
        Route::get('/{lichHen}', [\App\Http\Controllers\Doctor\LichHenController::class, 'show'])->name('show');
        Route::get('/confirmed/list', [\App\Http\Controllers\Doctor\LichHenController::class, 'confirmed'])->name('confirmed');
    });

    // Hàng đợi - Queue Management (File mẹ: routes/web.php)
    Route::prefix('queue')->name('queue.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Doctor\QueueController::class, 'index'])->name('index');
        Route::post('/{lichHen}/start', [\App\Http\Controllers\Doctor\QueueController::class, 'startExamination'])->name('start');
        Route::post('/{lichHen}/checkin', [\App\Http\Controllers\Doctor\QueueController::class, 'checkIn'])->name('checkin');
        Route::get('/count', [\App\Http\Controllers\Doctor\QueueController::class, 'getQueueCount'])->name('count');
    });

    // Đơn thuốc - Prescription Management (File mẹ: routes/web.php)
    Route::prefix('don-thuoc')->name('donthuoc.')->group(function () {
        Route::get('/{benhAn}/create', [\App\Http\Controllers\Doctor\DonThuocController::class, 'create'])->name('create');
        Route::post('/{benhAn}', [\App\Http\Controllers\Doctor\DonThuocController::class, 'store'])->name('store');
        Route::get('/{donThuoc}', [\App\Http\Controllers\Doctor\DonThuocController::class, 'show'])->name('show');
        Route::delete('/{donThuoc}', [\App\Http\Controllers\Doctor\DonThuocController::class, 'destroy'])->name('destroy');
        Route::get('/thuoc/{thuoc}/info', [\App\Http\Controllers\Doctor\DonThuocController::class, 'getThuocInfo'])->name('thuoc.info');
    });

    // Xét nghiệm - Lab Test Management (File mẹ: routes/web.php)
    Route::prefix('xet-nghiem')->name('xetnghiem.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Doctor\XetNghiemController::class, 'index'])->name('index');
        Route::get('/{benhAn}/create', [\App\Http\Controllers\Doctor\XetNghiemController::class, 'create'])->name('create');
        Route::post('/{benhAn}', [\App\Http\Controllers\Doctor\XetNghiemController::class, 'store'])->name('store');
        Route::get('/{xetNghiem}/show', [\App\Http\Controllers\Doctor\XetNghiemController::class, 'show'])->name('show');
        Route::post('/{xetNghiem}/upload', [\App\Http\Controllers\Doctor\XetNghiemController::class, 'uploadResult'])->name('upload');
        Route::get('/{xetNghiem}/download', [\App\Http\Controllers\Doctor\XetNghiemController::class, 'download'])->name('download');
        Route::delete('/{xetNghiem}', [\App\Http\Controllers\Doctor\XetNghiemController::class, 'destroy'])->name('destroy');
    });
});

// Patient
Route::middleware(['auth', 'role:patient'])->group(function () {
    Route::get('/patient/dashboard', [PatientDashboardController::class, 'index'])->name('patient.dashboard');
    Route::get('/dashboard/health', [PatientDashboardController::class, 'index'])->name('patient.dashboard.health');

    // Patient Lich Hen Routes (RESTful)
    Route::prefix('lich-hen')->name('patient.lichhen.')->group(function () {
        // ⚠️ QUAN TRỌNG: Route cụ thể phải đặt TRƯỚC route có param {lichHen}
        Route::get('/cua-toi', [LichHenController::class, 'myAppointments'])->name('my');

        Route::get('/', [LichHenController::class, 'myAppointments'])->name('index');
        Route::get('/create', [LichHenController::class, 'create'])->name('create');
        Route::post('/', [LichHenController::class, 'store'])->name('store');
        Route::get('/{lichHen}', [LichHenController::class, 'show'])->name('show');
        Route::get('/{lichHen}/edit', [LichHenController::class, 'edit'])->name('edit');
        Route::put('/{lichHen}', [LichHenController::class, 'update'])->name('update');
        Route::delete('/{lichHen}', [LichHenController::class, 'destroy'])->name('destroy');
        Route::patch('/{lichHen}/cancel', [LichHenController::class, 'cancel'])->name('cancel');

        // Workflow endpoints - Theo quy trình nghiệp vụ y tế
        Route::post('/{lichHen}/check-in', [LichHenController::class, 'checkIn'])->name('check-in');
    });

    // Legacy routes (backwards compatibility) - different paths to avoid conflicts
    Route::get('/lich-hen-cua-toi', [LichHenController::class, 'myAppointments'])->name('lichhen.my');
    // Route ajax.chuyenkhoa đã được định nghĩa ở dưới (dòng 539)
    Route::get('/ajax/bac-si/{bacSi}/lich-lam-viec', [LichHenController::class, 'getLichLamViec'])->name('ajax.lichlamviec');

    // Patient Reviews Routes
    Route::prefix('danhgia')->name('patient.danhgia.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Patient\DanhGiaController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Patient\DanhGiaController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Patient\DanhGiaController::class, 'store'])->name('store');
        Route::get('/{danhGia}/edit', [\App\Http\Controllers\Patient\DanhGiaController::class, 'edit'])->name('edit');
        Route::put('/{danhGia}', [\App\Http\Controllers\Patient\DanhGiaController::class, 'update'])->name('update');
        Route::delete('/{danhGia}', [\App\Http\Controllers\Patient\DanhGiaController::class, 'destroy'])->name('destroy');
    });

    // Patient Coupon Routes
    Route::prefix('coupons')->name('patient.coupons.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Patient\CouponController::class, 'index'])->name('index');
        Route::get('/{coupon}', [\App\Http\Controllers\Patient\CouponController::class, 'show'])->name('show');
        Route::post('/check', [\App\Http\Controllers\Patient\CouponController::class, 'check'])->name('check');
    });

    // Patient Shop Routes (mua thuốc) - CONSOLIDATED
    Route::prefix('shop')->name('patient.shop.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Patient\ShopController::class, 'index'])->name('index');
        Route::get('/cart', [\App\Http\Controllers\Patient\ShopController::class, 'cart'])->name('cart');
        Route::post('/cart/add/{id}', [\App\Http\Controllers\Patient\ShopController::class, 'addToCart'])->name('cart.add');
        Route::post('/cart/update', [\App\Http\Controllers\Patient\ShopController::class, 'updateCart'])->name('cart.update');
        Route::delete('/cart/remove/{id}', [\App\Http\Controllers\Patient\ShopController::class, 'removeFromCart'])->name('cart.remove');
        Route::get('/checkout', [\App\Http\Controllers\Patient\ShopController::class, 'checkout'])->name('checkout');
        Route::post('/checkout', [\App\Http\Controllers\Patient\ShopController::class, 'placeOrder'])->name('place-order');
        Route::get('/order-success/{id}', [\App\Http\Controllers\Patient\ShopController::class, 'orderSuccess'])->name('order-success');
        // Orders management
        Route::get('/orders', [\App\Http\Controllers\Patient\ShopController::class, 'orders'])->name('orders');
        Route::get('/orders/{donHang}', [\App\Http\Controllers\Patient\ShopController::class, 'orderDetail'])->name('order-detail');
        Route::delete('/orders/{donHang}/cancel', [\App\Http\Controllers\Patient\ShopController::class, 'cancelOrder'])->name('order-cancel');
    });

    // Patient Chat Routes
    Route::prefix('chat')->name('patient.chat.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Patient\ChatController::class, 'index'])->name('index');
        Route::get('/create/{bacSi}', [\App\Http\Controllers\Patient\ChatController::class, 'create'])->name('create');
        Route::get('/{conversation}', [\App\Http\Controllers\Patient\ChatController::class, 'show'])->name('show');
        Route::post('/{conversation}/send', [\App\Http\Controllers\Patient\ChatController::class, 'sendMessage'])->name('send');
        Route::get('/{conversation}/messages', [\App\Http\Controllers\Patient\ChatController::class, 'getMessages'])->name('messages');
    });

    // Patient Medical Records Routes
    Route::prefix('benh-an')->name('patient.benhan.')->group(function () {
        Route::get('/', [BenhAnController::class, 'index'])->name('index');
        Route::get('/{benh_an}', [BenhAnController::class, 'show'])->name('show');
        Route::get('/{benh_an}/export-pdf', [BenhAnController::class, 'exportPdf'])->name('exportPdf');
        Route::get('/file/{file}/download', [BenhAnController::class, 'downloadFile'])->name('files.download')->middleware('signed');
        Route::get('/xet-nghiem/{xetNghiem}/download', [BenhAnController::class, 'downloadXetNghiem'])->name('xetnghiem.download')->middleware('signed');
    });

    // Patient Invoice/HoaDon Routes
    Route::prefix('hoa-don')->name('patient.hoadon.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Patient\HoaDonController::class, 'index'])->name('index');
        Route::get('/{hoaDon}', [\App\Http\Controllers\Patient\HoaDonController::class, 'show'])->name('show');
    });

    // Patient Prescription Routes (File mẹ: routes/web.php)
    Route::prefix('don-thuoc')->name('patient.donthuoc.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Patient\DonThuocController::class, 'index'])->name('index');
        Route::get('/{donThuoc}', [\App\Http\Controllers\Patient\DonThuocController::class, 'show'])->name('show');
    });

    // Patient Test Results Routes (File mẹ: routes/web.php)
    Route::prefix('xet-nghiem')->name('patient.xetnghiem.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Patient\XetNghiemController::class, 'index'])->name('index');
        Route::get('/{xetNghiem}', [\App\Http\Controllers\Patient\XetNghiemController::class, 'show'])->name('show');
    });

    // Patient Notifications Routes (File mẹ: routes/web.php)
    Route::get('/thong-bao', [\App\Http\Controllers\Patient\NotificationController::class, 'index'])->name('patient.notifications');
    Route::post('/thong-bao/mark-all-read', [\App\Http\Controllers\Patient\NotificationController::class, 'markAllRead'])->name('patient.notifications.mark-all-read');
    Route::post('/thong-bao/{notification}/mark-read', [\App\Http\Controllers\Patient\NotificationController::class, 'markRead'])->name('patient.notifications.mark-read');
    Route::delete('/thong-bao/{notification}', [\App\Http\Controllers\Patient\NotificationController::class, 'delete'])->name('patient.notifications.delete');

    // Patient Medical History Routes (File mẹ: routes/web.php)
    Route::get('/lich-su-kham', [\App\Http\Controllers\Patient\LichSuKhamController::class, 'index'])->name('patient.lich-su-kham');

    // Patient Family Members Routes (File mẹ: routes/web.php)
    Route::prefix('thanh-vien-gia-dinh')->name('patient.family.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Patient\FamilyController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Patient\FamilyController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Patient\FamilyController::class, 'store'])->name('store');
        Route::get('/{member}/edit', [\App\Http\Controllers\Patient\FamilyController::class, 'edit'])->name('edit');
        Route::put('/{member}', [\App\Http\Controllers\Patient\FamilyController::class, 'update'])->name('update');
        Route::delete('/{member}', [\App\Http\Controllers\Patient\FamilyController::class, 'destroy'])->name('destroy');
    });

    Route::get('/lich-hen/{lichHen}/thanh-toan', [PatientPaymentController::class, 'show'])->name('patient.payment');
    Route::post('/lich-hen/{lichHen}/thanh-toan/skip', [PatientPaymentController::class, 'skip'])->name('patient.payment.skip');
});

// Staff: cho phép nhân viên xem Bệnh án (chỉ read/download)
Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::prefix('benh-an')->name('benhan.')->group(function () {
        Route::get('/', [BenhAnController::class, 'index'])->name('index');
        Route::get('/{benh_an}', [BenhAnController::class, 'show'])->name('show');
        Route::get('/file/{file}/download', [BenhAnController::class, 'downloadFile'])->name('files.download')->middleware('signed');
        Route::get('/xet-nghiem/{xetNghiem}/download', [BenhAnController::class, 'downloadXetNghiem'])->name('xetnghiem.download')->middleware('signed');
    });
});

Route::get('/doctor/{id}/schedule', function (int $id) { return view('doctor.schedule'); })->middleware(['auth', 'can:view-doctor-schedule,id'])->name('doctor.schedule');
Route::middleware('auth')->group(function () {
    // Route /lich-hen/cua-toi đã được định nghĩa trong patient group ở trên
    // Giữ lại các route khác
    Route::put('/lich-hen/{id}', [LichHenController::class, 'update'])->name('lichhen.update');
    Route::patch('/lich-hen/{id}/huy', [LichHenController::class, 'cancel'])->name('lichhen.cancel');
    Route::get('/benh-an/{benhAn}/don-thuoc/create', [BenhAnController::class, 'createPrescription'])->name('benhan.donthuoc.create');
    Route::post('/benh-an/{benhAn}/don-thuoc', [BenhAnController::class, 'storePrescription'])->name('benhan.donthuoc.store');
    Route::post('/don-thuoc/{donThuoc}/items', [BenhAnController::class, 'addPrescriptionItem'])->name('donthuoc.item.add');
    Route::post('/benh-an/{benhAn}/xet-nghiem', [BenhAnController::class, 'uploadXetNghiem'])->name('benhan.xetnghiem.upload');

    // Patient Profile Routes
    Route::post('/profile/medical', [ProfileController::class, 'updateMedical'])->name('profile.updateMedical');
    Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.uploadAvatar');
    Route::post('/profile/notifications', [ProfileController::class, 'updateNotifications'])->name('profile.updateNotifications');
});
Route::get('/ajax/chuyen-khoa', [LichHenController::class, 'ajaxChuyenKhoa'])->name('ajax.chuyenkhoa');
Route::get('/ajax/bac-si-by-chuyen-khoa', [LichHenController::class, 'ajaxBacSiByChuyenKhoa'])->name('ajax.bacsi.byChuyenKhoa');

// Legacy routes & UI cho Calendar cũ
Route::middleware(['auth','permission:view-dashboard'])->prefix('admin/calendar')->group(function () {
    Route::get('/', [CalendarController::class, 'index'])->name('admin.calendar.index');
    Route::get('events', [\App\Http\Controllers\Admin\CalendarController::class,'apiEvents'])->name('admin.calendar.events');
    Route::get('stats', [\App\Http\Controllers\Admin\CalendarController::class,'apiStats'])->name('admin.calendar.stats');
});

// Add alias routes so role-prefixed view route names resolve correctly
Route::get('/doctor/benh-an/xet-nghiem/{xetNghiem}/download', [\App\Http\Controllers\BenhAnController::class, 'downloadXetNghiem'])
    ->name('doctor.benhan.xetnghiem.download')
    ->middleware('signed');

Route::get('/patient/benh-an/xet-nghiem/{xetNghiem}/download', [\App\Http\Controllers\BenhAnController::class, 'downloadXetNghiem'])
    ->name('patient.benhan.xetnghiem.download')
    ->middleware('signed');

// Alias routes for file downloads so role-prefixed temporary signed routes resolve
Route::get('/doctor/benh-an/file/{file}/download', [\App\Http\Controllers\BenhAnController::class, 'downloadFile'])
    ->name('doctor.benhan.files.download')
    ->middleware('signed');

Route::get('/patient/benh-an/file/{file}/download', [\App\Http\Controllers\BenhAnController::class, 'downloadFile'])
    ->name('patient.benhan.files.download')
    ->middleware('signed');

Route::get('/staff/benh-an/file/{file}/download', [\App\Http\Controllers\BenhAnController::class, 'downloadFile'])
    ->name('staff.benhan.files.download')
    ->middleware('signed');

require __DIR__ . '/auth.php';

// CMS Public
Route::prefix('blog')->name('blog.')->group(function(){
    Route::get('/', [\App\Http\Controllers\BlogController::class, 'index'])->name('index');
    Route::get('/bai/{baiViet}', [\App\Http\Controllers\BlogController::class, 'show'])->name('show');
    Route::get('/danh-muc/{danhMuc}', [\App\Http\Controllers\BlogController::class, 'category'])->name('category');
    Route::get('/tag/{tag}', [\App\Http\Controllers\BlogController::class, 'tag'])->name('tag');
});

Route::middleware(['auth','verified'])->get('/lich-hen/lich', [\App\Http\Controllers\LichHenController::class, 'publicCalendar'])->name('public.lichhen.calendar');
Route::middleware(['auth','verified','signed'])->get('/tools/test-reminder', function(){
    if (! request()->hasValidSignature()) { abort(403); }
    $id = (int) request('id'); $type = request('type', '24h');
    $lh = LichHen::with('user')->findOrFail($id);
    $key = 'reminder:manual:'.$lh->id.':'.$type.':'.date('YmdHi');
    if (Cache::add($key, 1, now()->addMinutes(10))) { optional($lh->user)->notify(new AppointmentReminder($lh, $type)); }
    return response()->json(['ok' => true]);
})->name('tools.test-reminder');
Route::middleware(['auth','verified'])->get('/tools/make-test-reminder-url', function(){
    $id = (int) request('id'); $type = request('type', '24h');
    $url = URL::signedRoute('tools.test-reminder', compact('id','type'));
    return response()->json(['url' => $url]);
})->name('tools.make-test-reminder-url');
Route::get('/tin-tuc', [\App\Http\Controllers\BlogController::class, 'index'])->name('public.blog.index');

// Sitemap.xml
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

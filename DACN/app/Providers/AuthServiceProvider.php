<?php

namespace App\Providers;

use App\Models\BenhAn;
use App\Models\NhanVien;
use App\Models\LichHen;
use App\Models\SieuAm;
use App\Models\TaiKham;
use App\Models\TheoDoiThaiKy;
use App\Policies\BenhAnPolicy;
use App\Policies\NhanVienPolicy;
use App\Policies\LichHenPolicy;
use App\Policies\SieuAmPolicy;
use App\Policies\TaiKhamPolicy;
use App\Policies\TheoDoiThaiKyPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        BenhAn::class => BenhAnPolicy::class,
        NhanVien::class => NhanVienPolicy::class,
        LichHen::class => LichHenPolicy::class,
        SieuAm::class => SieuAmPolicy::class,
        TheoDoiThaiKy::class => TheoDoiThaiKyPolicy::class,
        TaiKham::class => TaiKhamPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('manage-appointments', fn($user) => $user->hasAnyRole(['admin', 'staff']));
        Gate::define(
            'view-doctor-schedule',
            fn($user, $doctorId = null) =>
            $user->isAdmin() || $user->isStaff() || ($user->isDoctor() && (!$doctorId || (int)$user->id === (int)$doctorId))
        );
        Gate::define('manage-doctors', fn($user) => $user->isAdmin());
        Gate::define('manage-settings', fn($user) => $user->isAdmin());

        \Illuminate\Support\Facades\Gate::policy(NhanVien::class, NhanVienPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(LichHen::class, LichHenPolicy::class);

        // Phân quyền kho thuốc: nhập / xuất (có thể tinh chỉnh sau)
        Gate::define('kho-nhap', function($user){
            // Cho phép admin và staff nhập kho
            return $user->isAdmin() || $user->hasAnyRole(['staff']);
        });
        Gate::define('kho-xuat', function($user){
            // Chỉ admin được xuất, staff bị hạn chế (có thể mở rộng sau)
            return $user->isAdmin();
        });

        // THÊM: Gates cho permissions admin panel (đảm bảo views chỉ hiển thị khi có quyền)
        Gate::define('view-admin-dashboard', fn($user) => $user->hasPermissionTo('view-dashboard'));
        Gate::define('view-admin-doctors', fn($user) => $user->hasPermissionTo('view-doctors'));
        Gate::define('view-admin-reports', fn($user) => $user->hasPermissionTo('view-reports'));
        Gate::define('view-admin-invoices', fn($user) => $user->hasPermissionTo('view-invoices'));
        Gate::define('view-admin-medical-records', fn($user) => $user->hasPermissionTo('view-medical-records'));
        Gate::define('view-admin-services', fn($user) => $user->hasPermissionTo('view-services'));
        Gate::define('view-admin-medicines', fn($user) => $user->hasPermissionTo('view-medicines'));
        Gate::define('view-admin-staff', fn($user) => $user->hasPermissionTo('view-staff'));
        Gate::define('view-admin-users', fn($user) => $user->hasPermissionTo('view-users'));
        Gate::define('view-admin-appointments', fn($user) => $user->hasPermissionTo('view-appointments'));
        Gate::define('manage-admin-settings', fn($user) => $user->hasPermissionTo('manage-settings'));  // Thêm cho settings nếu cần

        // THÊM: Gate tổng hợp cho admin panel (fallback nếu thiếu permission cụ thể)
        Gate::define('access-admin-panel', fn($user) => $user->hasAnyRole(['admin', 'manager']) || $user->hasAnyPermission(['view-dashboard', 'view-doctors', 'view-reports']));

        // THÊM: Gate cho gửi thông báo/reminders (sử dụng permission 'send-notifications' đã có)
        Gate::define('send-reminders', fn($user) => $user->hasPermissionTo('send-notifications') || $user->isAdmin() || $user->hasRole('admin'));
    }
}

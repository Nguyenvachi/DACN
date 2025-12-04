<?php

namespace App\Providers;

use App\Models\BenhAn;
use App\Models\NhanVien;
use App\Policies\BenhAnPolicy;
use App\Policies\NhanVienPolicy;
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
        'App\Models\Model' => 'App\Policies\ModelPolicy',
        BenhAn::class => BenhAnPolicy::class,
        NhanVien::class => NhanVienPolicy::class,
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

        // Phân quyền kho thuốc: nhập / xuất (có thể tinh chỉnh sau)
        Gate::define('kho-nhap', function($user){
            // Cho phép admin và staff nhập kho
            return $user->isAdmin() || $user->hasAnyRole(['staff']);
        });
        Gate::define('kho-xuat', function($user){
            // Chỉ admin được xuất, staff bị hạn chế (có thể mở rộng sau)
            return $user->isAdmin();
        });
    }
}

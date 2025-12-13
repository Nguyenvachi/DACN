<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    // protected $routeMiddleware = [
    //     'auth' => \App\Http\Middleware\Authenticate::class,
    //     'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
    //     'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
    //     'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
    //     'can' => \Illuminate\Auth\Middleware\Authorize::class,
    //     'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
    //     'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
    //     'signed' => \App\Http\Middleware\ValidateSignature::class,
    //     'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
    //     'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    //     'admin' => \App\Http\Middleware\IsAdmin::class, // THÊM DÒNG NÀY
    //     'role' => \App\Http\Middleware\RoleMiddleware::class, // THÊM DÒNG NÀY
    //     'is_admin' => \App\Http\Middleware\IsAdmin::class, // THÊM DÒNG NÀY
    //     'check.locked' => \App\Http\Middleware\CheckAccountLocked::class, // Middleware bảo mật tài khoản
    //     'must.change.password' => \App\Http\Middleware\MustChangePassword::class, // Middleware bắt đổi password
    // ];

    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        // --- CÁC MIDDLEWARE CỦA BẠN (CŨ) ---
        'admin' => \App\Http\Middleware\IsAdmin::class,
        'is_admin' => \App\Http\Middleware\IsAdmin::class,
        'check.locked' => \App\Http\Middleware\CheckAccountLocked::class,
        'must.change.password' => \App\Http\Middleware\MustChangePassword::class,
        'custom_role' => \App\Http\Middleware\RoleMiddleware::class, // Custom role middleware

        // --- 👇 CẬP NHẬT 3 DÒNG NÀY CHO THƯ VIỆN SPATIE 👇 ---
        // (Lưu ý namespace là Spatie\Permission\...)
        'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
        'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
        'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
    ];
}

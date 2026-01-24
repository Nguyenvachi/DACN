<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Models\LichHen;
use App\Models\HoaDon;
use App\Models\BenhAn;
use App\Models\NhanVien;
use App\Models\TheoDoiThaiKy;
use App\Observers\LichHenObserver;
use App\Observers\HoaDonObserver;
use App\Observers\BenhAnObserver;
use App\Observers\NhanVienObserver;
use App\Observers\TheoDoiThaiKyObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Sử dụng view pagination mặc định của Bootstrap để tránh xung đột Tailwind/Bootstrap
        Paginator::useBootstrap();

        // Load global helpers (number-to-words, QR helper)
        if (file_exists(app_path('Helpers/format_helpers.php'))) {
            require_once app_path('Helpers/format_helpers.php');
        }

        LichHen::observe(LichHenObserver::class);
        HoaDon::observe(HoaDonObserver::class);
        BenhAn::observe(BenhAnObserver::class);
        NhanVien::observe(NhanVienObserver::class);
        TheoDoiThaiKy::observe(TheoDoiThaiKyObserver::class);

        // Share danh sách hồ sơ bệnh án của user đang đăng nhập cho view profile.edit
        // NOTE: avoid calling dynamic relation on possibly untyped auth()->user() to satisfy static analysis (Intelephense)
        View::composer('profile.edit', function ($view) {
            $user = auth()->user();
            // Use BenhAn query by user_id to avoid IDE warning about undefined method on $user
            $benhAns = $user ? BenhAn::where('user_id', $user->id)->orderBy('ngay_kham', 'desc')->get() : collect();
            $view->with('benhAns', $benhAns);
        });
    }
}

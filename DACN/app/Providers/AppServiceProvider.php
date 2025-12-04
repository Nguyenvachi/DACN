<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\LichHen;
use App\Models\HoaDon;
use App\Models\BenhAn;
use App\Models\NhanVien;
use App\Observers\LichHenObserver;
use App\Observers\HoaDonObserver;
use App\Observers\BenhAnObserver;
use App\Observers\NhanVienObserver;

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
    }
}

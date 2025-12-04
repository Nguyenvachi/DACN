<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('lich_hens')) {
            Schema::table('lich_hens', function (Blueprint $table) {
                if (!Schema::hasColumn('lich_hens', 'bac_si_id')) {
                    $table->unsignedBigInteger('bac_si_id')->nullable()->index();
                }
                if (!Schema::hasColumn('lich_hens', 'ngay_hen')) {
                    $table->date('ngay_hen')->nullable()->index();
                }
                if (!Schema::hasColumn('lich_hens', 'thoi_gian_hen')) {
                    $table->time('thoi_gian_hen')->nullable();
                }
                if (!Schema::hasColumn('lich_hens', 'trang_thai')) {
                    $table->string('trang_thai')->nullable()->index();
                }
                if (!Schema::hasColumn('lich_hens', 'dich_vu_id')) {
                    $table->unsignedBigInteger('dich_vu_id')->nullable()->index();
                }
            });
        }
    }

    public function down(): void
    {
        // Không xóa cột (theo yêu cầu chỉ thêm)
    }
};
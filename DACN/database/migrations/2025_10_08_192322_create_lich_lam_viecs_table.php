<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('lich_lam_viecs')) {
            Schema::table('lich_lam_viecs', function (Blueprint $table) {
                if (!Schema::hasColumn('lich_lam_viecs', 'ngay_trong_tuan')) {
                    $table->tinyInteger('ngay_trong_tuan')->nullable()->index();
                }
                if (!Schema::hasColumn('lich_lam_viecs', 'thoi_gian_bat_dau')) {
                    $table->time('thoi_gian_bat_dau')->nullable();
                }
                if (!Schema::hasColumn('lich_lam_viecs', 'thoi_gian_ket_thuc')) {
                    $table->time('thoi_gian_ket_thuc')->nullable();
                }
            });
        }
    }
    public function down(): void
    {
        // Không xóa
    }
};

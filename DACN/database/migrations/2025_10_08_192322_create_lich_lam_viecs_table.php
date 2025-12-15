<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // If the table doesn't exist yet, create it (initial migration may be missing in some branches)
        if (!Schema::hasTable('lich_lam_viecs')) {
            Schema::create('lich_lam_viecs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('bac_si_id');
                $table->tinyInteger('ngay_trong_tuan')->nullable()->index();
                $table->time('thoi_gian_bat_dau')->nullable();
                $table->time('thoi_gian_ket_thuc')->nullable();
                $table->timestamps();

                $table->foreign('bac_si_id')->references('id')->on('bac_sis')->cascadeOnDelete();
            });
            return;
        }

        // Otherwise, ensure expected columns exist (upgrade path)
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
    public function down(): void
    {
        // Không xóa
    }
};

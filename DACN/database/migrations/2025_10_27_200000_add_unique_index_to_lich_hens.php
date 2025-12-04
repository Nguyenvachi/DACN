<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('lich_hens', function (Blueprint $table) {
            // Thêm chỉ mục unique để tránh duplicate bookings
            $table->unique(['bac_si_id', 'ngay_hen', 'thoi_gian_hen'], 'lich_hens_unique_bacsi_ngay_gio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('lich_hens', function (Blueprint $table) {
            $table->dropUnique('lich_hens_unique_bacsi_ngay_gio');
        });
    }
};

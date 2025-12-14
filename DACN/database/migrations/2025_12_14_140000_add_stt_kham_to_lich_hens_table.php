<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lich_hens', function (Blueprint $table) {
            $table->unsignedInteger('stt_kham')->nullable()->after('trang_thai')->comment('Số thứ tự khám trong ngày (theo bác sĩ)');

            // Add index for faster queries
            $table->index(['bac_si_id', 'ngay_hen', 'stt_kham'], 'idx_bac_si_ngay_stt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lich_hens', function (Blueprint $table) {
            $table->dropIndex('idx_bac_si_ngay_stt');
            $table->dropColumn('stt_kham');
        });
    }
};

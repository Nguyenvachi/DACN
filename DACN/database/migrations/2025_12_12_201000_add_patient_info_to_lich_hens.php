<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lich_hens', function (Blueprint $table) {
            // Add patient info to record snapshot of patient data when booking
            if (!Schema::hasColumn('lich_hens', 'ten_benh_nhan')) {
                $table->string('ten_benh_nhan')->nullable()->after('dich_vu_id');
            }
            if (!Schema::hasColumn('lich_hens', 'sdt_benh_nhan')) {
                $table->string('sdt_benh_nhan')->nullable()->after('ten_benh_nhan');
            }
            if (!Schema::hasColumn('lich_hens', 'email_benh_nhan')) {
                $table->string('email_benh_nhan')->nullable()->after('sdt_benh_nhan');
            }
            if (!Schema::hasColumn('lich_hens', 'ngay_sinh_benh_nhan')) {
                $table->date('ngay_sinh_benh_nhan')->nullable()->after('email_benh_nhan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('lich_hens', function (Blueprint $table) {
            if (Schema::hasColumn('lich_hens', 'ten_benh_nhan')) $table->dropColumn('ten_benh_nhan');
            if (Schema::hasColumn('lich_hens', 'sdt_benh_nhan')) $table->dropColumn('sdt_benh_nhan');
            if (Schema::hasColumn('lich_hens', 'email_benh_nhan')) $table->dropColumn('email_benh_nhan');
            if (Schema::hasColumn('lich_hens', 'ngay_sinh_benh_nhan')) $table->dropColumn('ngay_sinh_benh_nhan');
        });
    }
};

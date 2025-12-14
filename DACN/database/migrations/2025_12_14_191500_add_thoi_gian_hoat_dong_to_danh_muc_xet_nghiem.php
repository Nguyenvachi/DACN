<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('danh_muc_xet_nghiem', function (Blueprint $table) {
            $table->integer('thoi_gian')->default(30)->after('gia_tien')->comment('Thời gian ước tính (phút)');
            $table->boolean('hoat_dong')->default(true)->after('thoi_gian')->comment('Trạng thái hoạt động');
        });
    }

    public function down(): void
    {
        Schema::table('danh_muc_xet_nghiem', function (Blueprint $table) {
            $table->dropColumn(['thoi_gian', 'hoat_dong']);
        });
    }
};

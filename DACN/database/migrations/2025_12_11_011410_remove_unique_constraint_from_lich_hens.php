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
    public function up()
    {
        Schema::table('lich_hens', function (Blueprint $table) {
            // Xóa unique constraint để cho phép nhiều người đặt cùng khung giờ (tối đa 2 slot)
            $table->dropUnique('lich_hens_unique_bacsi_ngay_gio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lich_hens', function (Blueprint $table) {
            // Khôi phục lại unique constraint
            $table->unique(['bac_si_id', 'ngay_hen', 'thoi_gian_hen'], 'lich_hens_unique_bacsi_ngay_gio');
        });
    }
};

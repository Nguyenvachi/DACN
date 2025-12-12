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
            $table->string('ho_ten_benh_nhan')->nullable()->after('ghi_chu');
            $table->string('so_dien_thoai_benh_nhan', 20)->nullable()->after('ho_ten_benh_nhan');
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
            $table->dropColumn(['ho_ten_benh_nhan', 'so_dien_thoai_benh_nhan']);
        });
    }
};

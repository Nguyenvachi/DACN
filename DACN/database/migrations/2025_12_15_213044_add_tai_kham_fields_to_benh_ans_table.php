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
        Schema::table('benh_ans', function (Blueprint $table) {
            $table->date('ngay_hen_tai_kham')->nullable()->after('ghi_chu')->comment('Ngày hẹn tái khám');
            $table->text('ly_do_tai_kham')->nullable()->after('ngay_hen_tai_kham')->comment('Lý do/ghi chú tái khám');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('benh_ans', function (Blueprint $table) {
            $table->dropColumn(['ngay_hen_tai_kham', 'ly_do_tai_kham']);
        });
    }
};

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
        Schema::table('xet_nghiems', function (Blueprint $table) {
            // Thêm cột trạng thái xét nghiệm
            $table->string('trang_thai')->default('pending')->after('mo_ta');
            // pending: Chờ thực hiện
            // processing: Đang xử lý
            // completed: Đã có kết quả
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('xet_nghiems', function (Blueprint $table) {
            $table->dropColumn('trang_thai');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Sửa giá trị 'Hoàn thành' thành 'Đã hoàn thành'
        DB::statement("ALTER TABLE thu_thuats MODIFY COLUMN trang_thai ENUM('Chờ thực hiện', 'Đang thực hiện', 'Đã hoàn thành', 'Đã hủy') DEFAULT 'Chờ thực hiện'");

        // Cập nhật dữ liệu cũ nếu có
        DB::table('thu_thuats')->where('trang_thai', 'Hoàn thành')->update(['trang_thai' => 'Đã hoàn thành']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE thu_thuats MODIFY COLUMN trang_thai ENUM('Chờ thực hiện', 'Đang thực hiện', 'Hoàn thành', 'Đã hủy') DEFAULT 'Chờ thực hiện'");

        // Rollback dữ liệu
        DB::table('thu_thuats')->where('trang_thai', 'Đã hoàn thành')->update(['trang_thai' => 'Hoàn thành']);
    }
};

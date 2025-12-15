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
        Schema::table('don_hangs', function (Blueprint $table) {
            // Cập nhật enum để thêm 'Đang thanh toán'
            DB::statement("ALTER TABLE don_hangs MODIFY COLUMN trang_thai_thanh_toan ENUM('Chưa thanh toán', 'Đang thanh toán', 'Đã thanh toán', 'Hoàn tiền') DEFAULT 'Chưa thanh toán'");
        });
    }

    public function down()
    {
        Schema::table('don_hangs', function (Blueprint $table) {
            // Revert về enum cũ
            DB::statement("ALTER TABLE don_hangs MODIFY COLUMN trang_thai_thanh_toan ENUM('Chưa thanh toán', 'Đã thanh toán', 'Hoàn tiền') DEFAULT 'Chưa thanh toán'");
        });
    }
};

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
        Schema::table('users', function (Blueprint $table) {
            // Thông tin cơ bản của bệnh nhân để hỗ trợ lộ trình khám bệnh
            $table->string('so_dien_thoai', 15)->nullable()->after('email')->comment('Số điện thoại liên lạc');
            $table->date('ngay_sinh')->nullable()->after('so_dien_thoai')->comment('Ngày sinh');
            $table->enum('gioi_tinh', ['Nam', 'Nữ', 'Khác'])->nullable()->after('ngay_sinh')->comment('Giới tính');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['so_dien_thoai', 'ngay_sinh', 'gioi_tinh']);
        });
    }
};

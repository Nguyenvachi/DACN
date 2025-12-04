<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bac_sis', function (Blueprint $table) {
            $table->string('email')->nullable()->after('ho_ten');
            $table->text('dia_chi')->nullable()->after('so_dien_thoai');
            $table->integer('kinh_nghiem')->default(0)->comment('Số năm kinh nghiệm')->after('chuyen_khoa');
            $table->text('mo_ta')->nullable()->after('kinh_nghiem');
            $table->enum('trang_thai', ['Đang hoạt động', 'Ngừng hoạt động'])->default('Đang hoạt động')->after('mo_ta');
        });
    }

    public function down()
    {
        Schema::table('bac_sis', function (Blueprint $table) {
            $table->dropColumn(['email', 'dia_chi', 'kinh_nghiem', 'mo_ta', 'trang_thai']);
        });
    }
};

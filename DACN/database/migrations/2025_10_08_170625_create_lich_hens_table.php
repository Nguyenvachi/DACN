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
    public function up(): void
    {
        Schema::create('lich_hens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users'); // Khóa ngoại tới bảng users
            $table->foreignId('bac_si_id')->constrained('bac_sis'); // Khóa ngoại tới bảng bac_sis
            $table->foreignId('dich_vu_id')->constrained('dich_vus'); // Khóa ngoại tới bảng dich_vus
            $table->date('ngay_hen'); // Ngày hẹn
            $table->time('thoi_gian_hen'); // Giờ hẹn
            $table->text('ghi_chu')->nullable(); // Ghi chú của bệnh nhân
            $table->string('trang_thai')->default('Chờ xác nhận'); // Trạng thái: Chờ xác nhận, Đã xác nhận, Đã hủy, Hoàn thành
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lich_hens');
    }
};

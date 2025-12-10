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
        Schema::create('benh_an_dich_vu_nang_cao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('benh_an_id')->constrained('benh_ans')->onDelete('cascade');
            $table->foreignId('dich_vu_id')->constrained('dich_vus')->onDelete('cascade');
            $table->decimal('gia_tai_thoi_diem', 10, 2); // Giá dịch vụ tại thời điểm chỉ định
            $table->enum('trang_thai', ['Chờ thực hiện', 'Đang thực hiện', 'Hoàn thành', 'Đã hủy'])->default('Chờ thực hiện');
            $table->text('ghi_chu')->nullable(); // Ghi chú của bác sĩ khi chỉ định
            $table->text('ket_qua')->nullable(); // Kết quả sau khi thực hiện (nếu có)
            $table->timestamp('thoi_gian_thuc_hien')->nullable(); // Thời gian thực hiện dịch vụ
            $table->foreignId('nguoi_thuc_hien_id')->nullable()->constrained('users')->onDelete('set null'); // Người thực hiện dịch vụ
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
        Schema::dropIfExists('benh_an_dich_vu_nang_cao');
    }
};

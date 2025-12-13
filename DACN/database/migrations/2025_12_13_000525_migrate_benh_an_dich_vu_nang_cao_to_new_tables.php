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
        // Migration script - chuyển dữ liệu từ benh_an_dich_vu_nang_cao sang các bảng chuyên biệt
        // Vì dữ liệu cũ chưa có loại cụ thể, ta sẽ để trống (không migrate)
        // Chỉ drop bảng cũ sau khi đã kiểm tra

        // Xóa bảng benh_an_dich_vu_nang_cao
        Schema::dropIfExists('benh_an_dich_vu_nang_cao');
    }

    public function down()
    {
        // Không thể rollback vì đã xóa dữ liệu
        // Cần tạo lại bảng cũ nếu cần
        Schema::create('benh_an_dich_vu_nang_cao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('benh_an_id')->constrained('benh_ans')->onDelete('cascade');
            $table->foreignId('dich_vu_id')->constrained('dich_vus')->onDelete('cascade');
            $table->decimal('gia_tai_thoi_diem', 10, 2);
            $table->enum('trang_thai', ['Chờ thực hiện', 'Đang thực hiện', 'Hoàn thành', 'Đã hủy'])->default('Chờ thực hiện');
            $table->text('ghi_chu')->nullable();
            $table->text('ket_qua')->nullable();
            $table->timestamp('thoi_gian_thuc_hien')->nullable();
            $table->foreignId('nguoi_thuc_hien_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }
};

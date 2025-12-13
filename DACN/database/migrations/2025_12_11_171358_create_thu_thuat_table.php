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
        Schema::create('thu_thuats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('benh_an_id')->constrained('benh_ans')->onDelete('cascade');
            $table->foreignId('bac_si_id')->constrained('bac_sis')->onDelete('cascade');
            $table->string('ten_thu_thuat'); // 'Chọc ối', 'Sinh thiết nhau thai', 'Đo độ mở da gáy'...
            $table->date('ngay_chi_dinh');
            $table->date('ngay_thuc_hien')->nullable();
            $table->enum('trang_thai', ['Chờ thực hiện', 'Đang thực hiện', 'Đã hoàn thành', 'Đã hủy'])->default('Chờ thực hiện');

            // Thông tin thủ thuật
            $table->text('chi_tiet_truoc_thu_thuat')->nullable(); // Chuẩn bị, điều kiện
            $table->text('mo_ta_quy_trinh')->nullable();
            $table->text('ket_qua')->nullable();
            $table->text('bien_chung')->nullable(); // Biến chứng nếu có
            $table->text('xu_tri')->nullable(); // Xử trí sau thủ thuật

            // Giá tiền
            $table->decimal('gia_tien', 10, 2);
            $table->enum('trang_thai_thanh_toan', ['Chưa thanh toán', 'Đã thanh toán'])->default('Chưa thanh toán');

            $table->text('ghi_chu')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['benh_an_id', 'trang_thai']);
            $table->index('ngay_chi_dinh');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('thu_thuats');
    }
};

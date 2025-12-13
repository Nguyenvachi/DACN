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
        Schema::create('noi_soi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('benh_an_id')->constrained('benh_ans')->onDelete('cascade');
            $table->foreignId('dich_vu_id')->nullable()->constrained('dich_vus')->onDelete('set null');
            $table->string('loai_noi_soi'); // Nội soi dạ dày, đại tràng, phế quản...
            $table->date('ngay_chi_dinh');
            $table->date('ngay_thuc_hien')->nullable();
            $table->foreignId('bac_si_chi_dinh_id')->constrained('bac_sis')->onDelete('cascade');
            $table->foreignId('bac_si_thuc_hien_id')->nullable()->constrained('bac_sis')->onDelete('set null');
            $table->text('chi_dinh')->nullable(); // Lý do chỉ định
            $table->text('chuan_bi')->nullable(); // Hướng dẫn chuẩn bị
            $table->enum('trang_thai', ['Chờ thực hiện', 'Đang thực hiện', 'Hoàn thành', 'Đã hủy'])->default('Chờ thực hiện');

            // Kết quả nội soi
            $table->text('mo_ta_hinh_anh')->nullable(); // Mô tả hình ảnh nội soi
            $table->text('ton_thuong')->nullable(); // Tổn thương phát hiện
            $table->text('chan_doan')->nullable(); // Chẩn đoán sau nội soi
            $table->text('sinh_thiet')->nullable(); // Có sinh thiết hay không
            $table->json('hinh_anh')->nullable(); // Đường dẫn hình ảnh
            $table->text('xu_tri')->nullable(); // Xử trí trong khi nội soi
            $table->text('bien_chung')->nullable(); // Biến chứng nếu có
            $table->text('ket_luan')->nullable();
            $table->text('de_nghi')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->timestamps();

            $table->index(['benh_an_id', 'ngay_chi_dinh']);
            $table->index('trang_thai');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('noi_soi');
    }
};

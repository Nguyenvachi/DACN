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
        Schema::create('xet_nghiem', function (Blueprint $table) {
            $table->id();
            $table->foreignId('benh_an_id')->constrained('benh_ans')->onDelete('cascade');
            $table->foreignId('dich_vu_id')->nullable()->constrained('dich_vus')->onDelete('set null');
            $table->string('loai_xet_nghiem'); // Xét nghiệm máu, nước tiểu, sinh hóa...
            $table->string('ten_xet_nghiem'); // Tên cụ thể
            $table->date('ngay_chi_dinh');
            $table->date('ngay_lay_mau')->nullable();
            $table->date('ngay_tra_ket_qua')->nullable();
            $table->foreignId('bac_si_chi_dinh_id')->constrained('bac_sis')->onDelete('cascade');
            $table->text('chi_dinh')->nullable(); // Lý do chỉ định
            $table->enum('trang_thai', ['Chờ lấy mẫu', 'Đã lấy mẫu', 'Đang xét nghiệm', 'Có kết quả', 'Đã hủy'])->default('Chờ lấy mẫu');
            $table->boolean('can_nhin_an')->default(false); // Yêu cầu nhịn ăn
            $table->text('chuan_bi')->nullable(); // Hướng dẫn chuẩn bị

            // Kết quả xét nghiệm
            $table->json('chi_so')->nullable(); // JSON lưu các chỉ số: [{"ten": "Hồng cầu", "ket_qua": "4.5", "don_vi": "T/L", "gia_tri_bt": "4.0-5.5"}]
            $table->text('nhan_xet')->nullable();
            $table->text('ket_luan')->nullable();
            $table->json('file_ket_qua')->nullable(); // File PDF kết quả
            $table->text('ghi_chu')->nullable();
            $table->timestamps();

            $table->index(['benh_an_id', 'ngay_chi_dinh']);
            $table->index('trang_thai');
            $table->index('loai_xet_nghiem');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('xet_nghiem');
    }
};

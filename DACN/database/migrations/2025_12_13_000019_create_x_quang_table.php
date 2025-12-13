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
        Schema::create('x_quang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('benh_an_id')->constrained('benh_ans')->onDelete('cascade');
            $table->foreignId('dich_vu_id')->nullable()->constrained('dich_vus')->onDelete('set null');
            $table->string('loai_x_quang'); // X-quang ngực, bụng, xương...
            $table->string('vi_tri'); // Vị trí chụp
            $table->date('ngay_chi_dinh');
            $table->date('ngay_chup')->nullable();
            $table->foreignId('bac_si_chi_dinh_id')->constrained('bac_sis')->onDelete('cascade');
            $table->foreignId('bac_si_doc_ket_qua_id')->nullable()->constrained('bac_sis')->onDelete('set null');
            $table->text('chi_dinh')->nullable(); // Lý do chỉ định
            $table->enum('trang_thai', ['Chờ chụp', 'Đã chụp', 'Đã có kết quả', 'Đã hủy'])->default('Chờ chụp');

            // Kết quả X-quang
            $table->string('ky_thuat')->nullable(); // Kỹ thuật chụp (1 tư thế, 2 tư thế...)
            $table->text('mo_ta_hinh_anh')->nullable(); // Mô tả hình ảnh X-quang
            $table->text('tim_mach')->nullable(); // Đánh giá tim mạch
            $table->text('phoi')->nullable(); // Đánh giá phổi
            $table->text('xuong_khop')->nullable(); // Đánh giá xương khớp
            $table->text('co_quan_khac')->nullable(); // Đánh giá cơ quan khác
            $table->text('chan_doan')->nullable(); // Chẩn đoán
            $table->json('hinh_anh')->nullable(); // Đường dẫn file ảnh
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
        Schema::dropIfExists('x_quang');
    }
};

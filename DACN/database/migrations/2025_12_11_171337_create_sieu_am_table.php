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
        Schema::create('sieu_ams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('benh_an_id')->constrained('benh_ans')->onDelete('cascade');
            $table->foreignId('bac_si_id')->constrained('bac_sis')->onDelete('cascade');
            $table->string('loai_sieu_am'); // '4D', 'Doppler', 'Thường', 'Thai nhi'
            $table->date('ngay_chi_dinh');
            $table->date('ngay_thuc_hien')->nullable();
            $table->enum('trang_thai', ['Chờ thực hiện', 'Đang thực hiện', 'Hoàn thành', 'Đã hủy'])->default('Chờ thực hiện');

            // Thông tin kết quả
            $table->text('ket_qua')->nullable();
            $table->text('nhan_xet')->nullable();
            $table->json('hinh_anh')->nullable(); // Lưu đường dẫn ảnh siêu âm

            // Thông tin thai nhi (nếu là siêu âm thai)
            $table->integer('tuoi_thai_tuan')->nullable(); // Tuổi thai tính bằng tuần
            $table->integer('tuoi_thai_ngay')->nullable(); // Số ngày thêm (0-6)
            $table->decimal('can_nang_uoc_tinh', 8, 2)->nullable(); // gram
            $table->decimal('chieu_dai_dau_mong', 8, 2)->nullable(); // mm (CRL)
            $table->decimal('duong_kinh_hai_dinh', 8, 2)->nullable(); // mm (BPD)
            $table->decimal('chu_vi_bung', 8, 2)->nullable(); // mm (AC)
            $table->decimal('chieu_dai_xuong_dui', 8, 2)->nullable(); // mm (FL)
            $table->decimal('luong_nuoc_oi', 8, 2)->nullable(); // ml (AFI)

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
        Schema::dropIfExists('sieu_ams');
    }
};

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
        Schema::create('theo_doi_thai_ky', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Bệnh nhân
            $table->foreignId('bac_si_id')->nullable()->constrained('bac_sis')->onDelete('set null'); // Bác sĩ phụ trách

            // Thông tin thai kỳ
            $table->date('ngay_kinh_cuoi')->comment('Ngày đầu kỳ kinh cuối (LMP - Last Menstrual Period)');
            $table->date('ngay_du_sinh')->comment('Ngày dự sinh (EDD - Estimated Delivery Date)');
            $table->integer('so_lan_mang_thai')->default(1)->comment('Para - số lần mang thai');
            $table->integer('so_lan_sinh')->default(0)->comment('Gravida - số lần sinh');
            $table->integer('so_con_song')->default(0)->comment('Số con còn sống');

            // Thông tin thai hiện tại
            $table->enum('loai_thai', ['Đơn thai', 'Song thai', 'Đa thai'])->default('Đơn thai');
            $table->string('nhom_mau', 10)->nullable()->comment('Nhóm máu mẹ');
            $table->string('rh', 10)->nullable()->comment('Rh (+/-)');

            // Chỉ số ban đầu
            $table->decimal('can_nang_truoc_mang_thai', 5, 2)->nullable()->comment('kg');
            $table->decimal('chieu_cao', 5, 2)->nullable()->comment('cm');
            $table->decimal('bmi_truoc_mang_thai', 5, 2)->nullable()->comment('BMI');

            // Tiền sử bệnh
            $table->text('tien_su_san_khoa')->nullable()->comment('Tiền sử sản khoa (sẩy thai, nạo, sinh mổ...)');
            $table->text('tien_su_benh_ly')->nullable()->comment('Tiền sử bệnh lý (tim mạch, đái tháo đường, cao huyết áp...)');
            $table->text('di_ung')->nullable()->comment('Tiền sử dị ứng thuốc/thực phẩm');

            // Tình trạng hiện tại
            $table->enum('trang_thai', ['Đang theo dõi', 'Đã sinh', 'Sẩy thai', 'Nạo thai', 'Chuyển viện'])->default('Đang theo dõi');
            $table->date('ngay_ket_thuc')->nullable()->comment('Ngày kết thúc theo dõi (sinh/sẩy/nạo)');
            $table->text('ket_qua_thai_ky')->nullable()->comment('Kết quả thai kỳ (sinh thường/mổ, cân nặng trẻ...)');

            // Ghi chú
            $table->text('ghi_chu')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('bac_si_id');
            $table->index('trang_thai');
            $table->index('ngay_du_sinh');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('theo_doi_thai_ky');
    }
};

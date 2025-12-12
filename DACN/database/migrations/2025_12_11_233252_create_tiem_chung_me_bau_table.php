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
        Schema::create('tiem_chung_me_bau', function (Blueprint $table) {
            $table->id();
            $table->foreignId('theo_doi_thai_ky_id')->constrained('theo_doi_thai_ky')->onDelete('cascade');
            $table->foreignId('bac_si_id')->nullable()->constrained('bac_sis')->onDelete('set null');

            // Thông tin vaccine
            $table->string('ten_vaccine')->comment('Tên vaccine (VD: Uốn ván, Cúm, COVID-19...)');
            $table->integer('tuan_thai_du_kien')->nullable()->comment('Tuần thai nên tiêm');
            $table->date('ngay_du_kien')->nullable()->comment('Ngày dự kiến tiêm');

            // Thông tin tiêm
            $table->date('ngay_tiem')->nullable()->comment('Ngày tiêm thực tế');
            $table->string('lo_vaccine', 100)->nullable()->comment('Số lô vaccine');
            $table->string('noi_tiem', 255)->nullable()->comment('Nơi tiêm');
            $table->string('nguoi_tiem', 255)->nullable()->comment('Người tiêm');

            // Mũi tiêm
            $table->integer('mui_so')->default(1)->comment('Mũi thứ mấy');
            $table->integer('tong_so_mui')->default(1)->comment('Tổng số mũi cần tiêm');
            $table->date('hen_mui_tiep')->nullable()->comment('Hẹn tiêm mũi tiếp theo');

            // Phản ứng sau tiêm
            $table->text('phan_ung_sau_tiem')->nullable()->comment('Phản ứng sau tiêm (nếu có)');
            $table->enum('muc_do_phan_ung', ['Không', 'Nhẹ', 'Trung bình', 'Nặng'])->default('Không');

            // Trạng thái
            $table->enum('trang_thai', ['Chưa tiêm', 'Đã tiêm', 'Bỏ lỡ', 'Chống chỉ định'])->default('Chưa tiêm');
            $table->text('ly_do_khong_tiem')->nullable()->comment('Lý do không tiêm (nếu có)');

            $table->text('ghi_chu')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('theo_doi_thai_ky_id');
            $table->index('ten_vaccine');
            $table->index('trang_thai');
            $table->index('ngay_du_kien');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tiem_chung_me_bau');
    }
};

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
        Schema::create('lich_tai_khams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('benh_an_id')->constrained('benh_ans')->onDelete('cascade');
            $table->foreignId('bac_si_id')->constrained('bac_sis')->onDelete('cascade');
            $table->foreignId('benh_nhan_id')->constrained('users')->onDelete('cascade');

            $table->date('ngay_hen');
            $table->time('gio_hen')->nullable();
            $table->text('ly_do'); // Lý do tái khám
            $table->text('luu_y')->nullable(); // Lưu ý cho bệnh nhân

            $table->enum('trang_thai', [
                'Đã hẹn',
                'Đã xác nhận',
                'Đã khám',
                'Đã hủy',
                'Quá hạn'
            ])->default('Đã hẹn');

            $table->text('ghi_chu')->nullable();
            $table->timestamp('ngay_xac_nhan')->nullable();
            $table->timestamp('ngay_kham_thuc_te')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['benh_nhan_id', 'ngay_hen']);
            $table->index(['bac_si_id', 'ngay_hen']);
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
        Schema::dropIfExists('lich_tai_khams');
    }
};

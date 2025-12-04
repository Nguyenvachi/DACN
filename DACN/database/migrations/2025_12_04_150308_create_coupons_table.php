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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('ma_giam_gia')->unique()->comment('Mã coupon (VD: KHAI2025, GIAM50K)');
            $table->string('ten')->comment('Tên chương trình');
            $table->text('mo_ta')->nullable();
            $table->enum('loai', ['phan_tram', 'tien_mat'])->default('phan_tram')->comment('% hoặc VNĐ');
            $table->decimal('gia_tri', 15, 2)->comment('Giá trị giảm (% hoặc VNĐ)');
            $table->decimal('giam_toi_da', 15, 2)->nullable()->comment('Giảm tối đa (cho loại %)');
            $table->decimal('don_toi_thieu', 15, 2)->nullable()->comment('Giá trị đơn hàng tối thiểu');
            $table->date('ngay_bat_dau')->comment('Ngày bắt đầu hiệu lực');
            $table->date('ngay_ket_thuc')->comment('Ngày hết hạn');
            $table->integer('so_lan_su_dung_toi_da')->nullable()->comment('Số lần sử dụng tối đa (null = không giới hạn)');
            $table->integer('so_lan_da_su_dung')->default(0)->comment('Số lần đã sử dụng');
            $table->boolean('kich_hoat')->default(true);
            $table->timestamps();

            $table->index(['ngay_bat_dau', 'ngay_ket_thuc']);
            $table->index('kich_hoat');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
};

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
        Schema::create('don_hangs', function (Blueprint $table) {
            $table->id();
            $table->string('ma_don_hang')->unique()->comment('Mã đơn hàng tự động');
            $table->foreignId('user_id')->constrained('users')->comment('Bệnh nhân đặt hàng');
            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->comment('Mã giảm giá (nếu có)');
            $table->decimal('tong_tien', 15, 2)->default(0)->comment('Tổng tiền hàng');
            $table->decimal('giam_gia', 15, 2)->default(0)->comment('Số tiền giảm giá');
            $table->decimal('thanh_toan', 15, 2)->default(0)->comment('Số tiền phải thanh toán (tong - giam)');
            $table->enum('trang_thai', ['Chờ xử lý', 'Đã xác nhận', 'Đang giao', 'Hoàn thành', 'Đã hủy'])->default('Chờ xử lý');
            $table->enum('trang_thai_thanh_toan', ['Chưa thanh toán', 'Đã thanh toán', 'Hoàn tiền'])->default('Chưa thanh toán');
            $table->string('dia_chi_giao')->nullable()->comment('Địa chỉ giao hàng');
            $table->string('sdt_nguoi_nhan')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->timestamp('ngay_dat')->useCurrent();
            $table->timestamp('ngay_giao_du_kien')->nullable();
            $table->timestamps();

            $table->index('trang_thai');
            $table->index('trang_thai_thanh_toan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('don_hangs');
    }
};

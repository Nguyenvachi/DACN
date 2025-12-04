<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('hoa_dons')) {
            Schema::create('hoa_dons', function (Blueprint $table) {
                $table->id();
                $table->foreignId('lich_hen_id')->constrained('lich_hens')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // bệnh nhân
                $table->decimal('tong_tien', 12, 2)->default(0);
                $table->string('trang_thai')->default('Chưa thanh toán'); // Chưa thanh toán | Đã thanh toán | Hoàn tiền | Thất bại
                $table->string('phuong_thuc')->nullable(); // Stripe | MoMo | ZaloPay | Tiền mặt
                $table->string('ghi_chu')->nullable();
                $table->timestamps();

                $table->unique('lich_hen_id'); // 1 lịch hẹn -> 1 hóa đơn
                $table->index(['user_id', 'trang_thai']);
            });
        }
    }

    public function down(): void
    {
        // Giữ trống để tuân thủ “chỉ thêm, không bớt”
    }
};

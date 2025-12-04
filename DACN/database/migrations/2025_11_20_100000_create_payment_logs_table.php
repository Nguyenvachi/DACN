<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Parent: database/migrations/
     * Child: 2025_11_20_100000_create_payment_logs_table.php
     * Purpose: Tạo bảng payment_logs để ghi lại tất cả các request/response thanh toán
     */
    public function up(): void
    {
        if (!Schema::hasTable('payment_logs')) {
            Schema::create('payment_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('hoa_don_id')->nullable()->constrained('hoa_dons')->onDelete('set null');
                $table->string('provider'); // vnpay|momo
                $table->string('event_type'); // request|return|ipn
                $table->string('idempotency_key')->nullable(); // Để track duplicate requests
                $table->string('transaction_ref')->nullable(); // Mã giao dịch từ gateway
                $table->string('result_code')->nullable(); // Mã kết quả
                $table->text('result_message')->nullable(); // Thông báo kết quả
                $table->json('payload'); // Toàn bộ request/response data
                $table->ipAddress('ip_address')->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamps();

                // Indexes để tìm kiếm nhanh
                $table->index(['hoa_don_id', 'event_type']);
                $table->index(['provider', 'transaction_ref']);
                $table->index('idempotency_key');
                $table->index('created_at');
            });
        }
    }

    public function down(): void
    {
        // Giữ trống để tuân thủ "chỉ thêm, không bớt"
    }
};

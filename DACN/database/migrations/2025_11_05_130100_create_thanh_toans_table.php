<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('thanh_toans')) {
            Schema::create('thanh_toans', function (Blueprint $table) {
                $table->id();
                $table->foreignId('hoa_don_id')->constrained('hoa_dons')->onDelete('cascade');
                $table->string('provider'); // stripe|momo|zalopay|cash
                $table->decimal('so_tien', 12, 2);
                $table->string('tien_te')->default('VND');
                $table->string('trang_thai')->default('pending'); // pending|succeeded|failed|refunded
                $table->string('transaction_ref')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->json('payload')->nullable();
                $table->timestamps();

                $table->index(['provider', 'transaction_ref']);
                $table->index(['hoa_don_id', 'trang_thai']);
            });
        }
    }

    public function down(): void
    {
        // Giữ trống để tuân thủ “chỉ thêm, không bớt”
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Parent: database/migrations/
     * Child: 2025_11_20_100100_add_idempotency_key_to_thanh_toans_table.php
     * Purpose: Thêm cột idempotency_key để tránh duplicate transaction từ webhook
     */
    public function up(): void
    {
        if (Schema::hasTable('thanh_toans')) {
            Schema::table('thanh_toans', function (Blueprint $table) {
                if (!Schema::hasColumn('thanh_toans', 'idempotency_key')) {
                    $table->string('idempotency_key')->nullable()->unique()->after('transaction_ref');
                }
            });
        }
    }

    public function down(): void
    {
        // Giữ trống để tuân thủ "chỉ thêm, không bớt"
    }
};

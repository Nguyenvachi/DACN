<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lich_hens', function (Blueprint $table) {
            $table->string('payment_status')->default('Chưa thanh toán');
            $table->string('payment_method')->nullable();
            $table->timestamp('payment_paid_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('lich_hens', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'payment_method', 'payment_paid_at']);
        });
    }
};

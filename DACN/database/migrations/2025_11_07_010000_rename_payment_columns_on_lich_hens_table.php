<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('lich_hens', 'payment_paid_at')) {
            DB::statement("ALTER TABLE `lich_hens` CHANGE `payment_paid_at` `paid_at` TIMESTAMP NULL DEFAULT NULL");
        } else {
            Schema::table('lich_hens', function (Blueprint $table) {
                $table->timestamp('paid_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('lich_hens', 'paid_at')) {
            DB::statement("ALTER TABLE `lich_hens` CHANGE `paid_at` `payment_paid_at` TIMESTAMP NULL DEFAULT NULL");
        }
    }
};

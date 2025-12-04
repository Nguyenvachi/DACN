<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('lich_hens', function (Blueprint $table) {
            if (!Schema::hasColumn('lich_hens', 'cancelled_by')) {
                $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('lich_hens', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable();
            }
        });
    }
    public function down(): void {}
};
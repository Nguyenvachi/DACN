<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasColumn('lich_hens', 'checked_in_by')) {
            Schema::table('lich_hens', function (Blueprint $table) {
                $table->unsignedBigInteger('checked_in_by')->nullable()->after('checked_in_at');
                $table->foreign('checked_in_by')->references('id')->on('users')->nullOnDelete();
            });
        }
    }

    public function down(): void {
        if (Schema::hasColumn('lich_hens', 'checked_in_by')) {
            Schema::table('lich_hens', function (Blueprint $table) {
                $table->dropForeign(['checked_in_by']);
                $table->dropColumn('checked_in_by');
            });
        }
    }
};

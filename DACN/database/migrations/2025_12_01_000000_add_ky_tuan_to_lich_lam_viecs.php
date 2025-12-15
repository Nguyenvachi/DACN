<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('lich_lam_viecs', 'ky_tuan')) {
            Schema::table('lich_lam_viecs', function (Blueprint $table) {
                $table->string('ky_tuan', 20)->nullable()->after('ngay_trong_tuan')
                    ->comment('Kỳ tuần: all (mọi tuần), odd (tuần lẻ), even (tuần chẵn)');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('lich_lam_viecs', 'ky_tuan')) {
            Schema::table('lich_lam_viecs', function (Blueprint $table) {
                $table->dropColumn('ky_tuan');
            });
        }
    }
};

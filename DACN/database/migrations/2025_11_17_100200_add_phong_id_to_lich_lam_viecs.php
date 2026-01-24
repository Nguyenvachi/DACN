<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('lich_lam_viecs') && !Schema::hasColumn('lich_lam_viecs','phong_id')) {
            Schema::table('lich_lam_viecs', function (Blueprint $table) {
                $table->unsignedBigInteger('phong_id')->nullable()->after('bac_si_id');
                $table->foreign('phong_id')->references('id')->on('phongs')->nullOnDelete();
            });
        }
    }
    public function down(): void
    {
        if (Schema::hasColumn('lich_lam_viecs','phong_id')) {
            Schema::table('lich_lam_viecs', function (Blueprint $table) {
                $table->dropForeign(['phong_id']);
                $table->dropColumn('phong_id');
            });
        }
    }
};

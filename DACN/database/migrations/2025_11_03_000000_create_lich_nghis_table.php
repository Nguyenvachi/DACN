<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('lich_nghis')) {
            Schema::table('lich_nghis', function (Blueprint $table) {
                if (!Schema::hasColumn('lich_nghis', 'bac_si_id')) {
                    $table->unsignedBigInteger('bac_si_id')->nullable()->index();
                }
                if (!Schema::hasColumn('lich_nghis', 'ngay')) {
                    $table->date('ngay')->nullable()->index();
                }
                if (!Schema::hasColumn('lich_nghis', 'bat_dau')) {
                    $table->time('bat_dau')->nullable();
                }
                if (!Schema::hasColumn('lich_nghis', 'ket_thuc')) {
                    $table->time('ket_thuc')->nullable();
                }
                if (!Schema::hasColumn('lich_nghis', 'ly_do')) {
                    $table->string('ly_do')->nullable();
                }
            });
        }
    }
    public function down(): void
    {
        // Không xóa
    }
};

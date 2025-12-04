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
        if (!Schema::hasTable('lich_nghis')) {
            Schema::create('lich_nghis', function (Blueprint $table) {
                $table->id();
                $table->foreignId('bac_si_id')->constrained('bac_sis')->cascadeOnDelete();
                $table->date('ngay');
                $table->time('bat_dau');
                $table->time('ket_thuc');
                $table->string('ly_do')->nullable();
                $table->timestamps();

                $table->index(['bac_si_id', 'ngay']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lich_nghis');
    }
};

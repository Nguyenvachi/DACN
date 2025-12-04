<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ca_dieu_chinh_bac_sis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bac_si_id')->constrained('bac_sis')->cascadeOnDelete();
            $table->date('ngay');
            $table->time('gio_bat_dau');
            $table->time('gio_ket_thuc');
            $table->enum('hanh_dong', ['add', 'modify', 'cancel'])->default('add');
            $table->string('ly_do')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['bac_si_id', 'ngay']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ca_dieu_chinh_bac_sis');
    }
};
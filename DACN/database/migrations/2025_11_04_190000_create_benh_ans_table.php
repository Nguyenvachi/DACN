<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('benh_ans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();       // bệnh nhân
            $table->foreignId('bac_si_id')->constrained('bac_sis')->cascadeOnDelete();    // bác sĩ phụ trách
            $table->foreignId('lich_hen_id')->nullable()->constrained('lich_hens')->nullOnDelete();
            $table->date('ngay_kham')->default(now());
            $table->string('tieu_de', 255);
            $table->text('trieu_chung')->nullable();
            $table->text('chuan_doan')->nullable();
            $table->text('dieu_tri')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'bac_si_id', 'ngay_kham']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('benh_ans');
    }
};

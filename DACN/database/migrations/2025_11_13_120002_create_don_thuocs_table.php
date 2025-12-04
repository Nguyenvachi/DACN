<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('don_thuocs')) {
            Schema::create('don_thuocs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('benh_an_id')->constrained('benh_ans')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();   // bệnh nhân (đúng quy ước hiện có)
                $table->foreignId('bac_si_id')->nullable()->constrained('bac_sis')->nullOnDelete();
                $table->foreignId('lich_hen_id')->nullable()->constrained('lich_hens')->nullOnDelete(); // liên kết nếu có
                $table->string('ghi_chu')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('don_thuoc_items')) {
            Schema::create('don_thuoc_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('don_thuoc_id')->constrained('don_thuocs')->cascadeOnDelete();
                $table->foreignId('thuoc_id')->constrained('thuocs')->cascadeOnDelete();
                $table->integer('so_luong');
                $table->string('lieu_dung')->nullable(); // 1 viên x 2 lần/ngày
                $table->string('cach_dung')->nullable(); // sau ăn
                $table->timestamps();
            });
        }
    }
    public function down(): void {}
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('xet_nghiems')) {
            Schema::create('xet_nghiems', function (Blueprint $table) {
                $table->id();
                $table->foreignId('benh_an_id')->constrained('benh_ans')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();   // bệnh nhân
                $table->foreignId('bac_si_id')->nullable()->constrained('bac_sis')->nullOnDelete(); // người chỉ định
                $table->string('loai');       // máu / sinh hóa / nước tiểu / ...
                $table->string('file_path');  // đường dẫn file kết quả
                $table->string('mo_ta')->nullable();
                $table->timestamps();
            });
        }
    }
    public function down(): void {}
};
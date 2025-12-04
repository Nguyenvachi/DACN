<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('nhan_viens')) {
            Schema::create('nhan_viens', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('ho_ten');
                $table->string('chuc_vu')->nullable();       // lễ tân, điều dưỡng...
                $table->string('so_dien_thoai')->nullable();
                $table->string('email_cong_viec')->nullable()->unique();
                $table->date('ngay_sinh')->nullable();
                $table->string('gioi_tinh', 10)->nullable(); // nam|nu|khac
                $table->string('avatar_path')->nullable();
                $table->string('trang_thai', 20)->default('active'); // active|inactive
                $table->timestamps();
            });
        }
    }
    public function down(): void {}
};
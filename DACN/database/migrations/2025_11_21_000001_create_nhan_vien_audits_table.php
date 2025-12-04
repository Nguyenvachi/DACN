<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nhan_vien_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nhan_vien_id')->constrained('nhan_viens')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // người thực hiện
            $table->string('action', 50); // created, updated, deleted
            $table->json('old_data')->nullable(); // dữ liệu cũ
            $table->json('new_data')->nullable(); // dữ liệu mới
            $table->timestamps();

            $table->index(['nhan_vien_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nhan_vien_audits');
    }
};

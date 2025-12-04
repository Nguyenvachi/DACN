<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('email')->nullable(); // Lưu email attempt (kể cả sai)
            $table->string('ip', 45);
            $table->text('user_agent')->nullable();
            $table->enum('status', ['success', 'failed'])->default('failed');
            $table->string('reason')->nullable(); // invalid_credentials, account_locked, etc.
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index('ip');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_audits');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('slot_locks')) {
            Schema::create('slot_locks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('bac_si_id')->constrained('bac_sis')->cascadeOnDelete();
                $table->date('ngay');
                $table->time('gio');
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();

                $table->unique(['bac_si_id', 'ngay', 'gio']);
                $table->index(['expires_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('slot_locks');
    }
};

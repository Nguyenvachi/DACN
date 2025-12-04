<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('thuocs')) {
            Schema::create('thuocs', function (Blueprint $table) {
                $table->id();
                $table->string('ten');
                $table->string('hoat_chat')->nullable();
                $table->string('ham_luong')->nullable();
                $table->string('don_vi')->default('viên');
                $table->decimal('gia_tham_khao', 12, 2)->nullable();
                $table->timestamps();
            });
        }
    }
    public function down(): void {}
};
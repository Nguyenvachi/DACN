<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('bai_viets')) {
            Schema::create('bai_viets', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('danh_muc_id')->nullable()->constrained('danh_mucs')->nullOnDelete();
                $table->string('title');
                $table->string('slug')->unique();
                $table->string('excerpt')->nullable();
                $table->longText('content');
                $table->enum('status', ['draft', 'published'])->default('draft');
                $table->timestamp('published_at')->nullable();
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->string('thumbnail')->nullable();
                $table->timestamps();
                $table->index(['status', 'published_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('bai_viets');
    }
};

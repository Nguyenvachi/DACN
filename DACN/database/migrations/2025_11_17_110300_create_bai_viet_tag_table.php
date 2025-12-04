<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('bai_viet_tag')) {
            Schema::create('bai_viet_tag', function (Blueprint $table) {
                $table->foreignId('bai_viet_id')->constrained('bai_viets')->cascadeOnDelete();
                $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
                $table->primary(['bai_viet_id', 'tag_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('bai_viet_tag');
    }
};

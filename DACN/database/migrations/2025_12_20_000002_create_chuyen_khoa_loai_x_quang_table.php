<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('chuyen_khoa_loai_x_quang')) {
            Schema::create('chuyen_khoa_loai_x_quang', function (Blueprint $table) {
                $table->id();
                $table->foreignId('chuyen_khoa_id')->constrained('chuyen_khoas')->cascadeOnDelete();
                $table->foreignId('loai_x_quang_id')->constrained('loai_x_quangs')->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['chuyen_khoa_id', 'loai_x_quang_id'], 'ck_lxq_unique');
            });
        }
    }

    public function down(): void
    {
        // Không rollback theo rule dự án: chỉ bổ sung code
    }
};

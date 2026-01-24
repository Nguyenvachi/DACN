<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('chuyen_khoa_loai_noi_soi')) {
            Schema::create('chuyen_khoa_loai_noi_soi', function (Blueprint $table) {
                $table->id();
                $table->foreignId('chuyen_khoa_id')->constrained('chuyen_khoas')->cascadeOnDelete();
                $table->foreignId('loai_noi_soi_id')->constrained('loai_noi_sois')->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['chuyen_khoa_id', 'loai_noi_soi_id'], 'ck_lns_unique');
            });
        }
    }

    public function down(): void
    {
        // Không rollback theo rule dự án: chỉ bổ sung code
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('noi_sois') && ! Schema::hasColumn('noi_sois', 'loai_noi_soi_id')) {
            Schema::table('noi_sois', function (Blueprint $table) {
                $table->foreignId('loai_noi_soi_id')
                    ->nullable()
                    ->after('phong_id')
                    ->constrained('loai_noi_sois')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        // Không rollback theo rule dự án: chỉ bổ sung code
    }
};

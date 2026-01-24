<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('don_thuocs')) {
            return;
        }

        Schema::table('don_thuocs', function (Blueprint $table) {
            if (! Schema::hasColumn('don_thuocs', 'trang_thai')) {
                $table->string('trang_thai')->nullable()->after('ghi_chu');
            }

            if (! Schema::hasColumn('don_thuocs', 'ngay_cap_thuoc')) {
                $table->dateTime('ngay_cap_thuoc')->nullable()->after('trang_thai');
            }

            if (! Schema::hasColumn('don_thuocs', 'nguoi_cap_thuoc_id')) {
                $table->foreignId('nguoi_cap_thuoc_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('don_thuocs', 'ghi_chu_cap_thuoc')) {
                $table->string('ghi_chu_cap_thuoc', 1000)->nullable()->after('ghi_chu');
            }
        });
    }

    public function down(): void
    {
        // Add-only migrations in this project.
    }
};

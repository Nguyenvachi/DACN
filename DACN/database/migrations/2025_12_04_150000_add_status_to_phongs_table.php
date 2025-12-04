<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Thêm trạng thái và thông tin mở rộng cho bảng phongs
 * Parent file: database/migrations/2025_12_04_150000_add_status_to_phongs_table.php
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('phongs', function (Blueprint $table) {
            if (!Schema::hasColumn('phongs', 'trang_thai')) {
                $table->enum('trang_thai', ['Sẵn sàng', 'Đang sử dụng', 'Bảo trì', 'Tạm ngưng'])
                    ->default('Sẵn sàng')
                    ->after('mo_ta')
                    ->comment('Trạng thái phòng');
            }

            if (!Schema::hasColumn('phongs', 'vi_tri')) {
                $table->string('vi_tri', 255)->nullable()->after('trang_thai')
                    ->comment('Vị trí phòng (Tầng 1, Tầng 2, etc.)');
            }

            if (!Schema::hasColumn('phongs', 'dien_tich')) {
                $table->decimal('dien_tich', 8, 2)->nullable()->after('vi_tri')
                    ->comment('Diện tích phòng (m²)');
            }

            if (!Schema::hasColumn('phongs', 'suc_chua')) {
                $table->integer('suc_chua')->nullable()->after('dien_tich')
                    ->comment('Sức chứa tối đa (số người)');
            }
        });
    }

    public function down(): void
    {
        Schema::table('phongs', function (Blueprint $table) {
            if (Schema::hasColumn('phongs', 'trang_thai')) {
                $table->dropColumn('trang_thai');
            }
            if (Schema::hasColumn('phongs', 'vi_tri')) {
                $table->dropColumn('vi_tri');
            }
            if (Schema::hasColumn('phongs', 'dien_tich')) {
                $table->dropColumn('dien_tich');
            }
            if (Schema::hasColumn('phongs', 'suc_chua')) {
                $table->dropColumn('suc_chua');
            }
        });
    }
};

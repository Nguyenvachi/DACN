<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('lich_lam_viecs', 'ky_tuan')) {
            Schema::table('lich_lam_viecs', function (Blueprint $table) {
                // Xóa cột cũ
                $table->dropColumn('ky_tuan');
            });
        }

        if (!Schema::hasColumn('lich_lam_viecs', 'thangs')) {
            Schema::table('lich_lam_viecs', function (Blueprint $table) {
                // Thêm cột mới: lưu danh sách tháng (JSON hoặc comma-separated)
                // NULL = áp dụng tất cả tháng, "1,2,3" = chỉ áp dụng tháng 1,2,3
                $table->string('thangs', 50)->nullable()->after('ngay_trong_tuan')
                    ->comment('Danh sách tháng áp dụng (1-12), cách nhau bởi dấu phẩy. NULL = tất cả tháng');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('lich_lam_viecs', 'thangs')) {
            Schema::table('lich_lam_viecs', function (Blueprint $table) {
                $table->dropColumn('thangs');
            });
        }

        if (!Schema::hasColumn('lich_lam_viecs', 'ky_tuan')) {
            Schema::table('lich_lam_viecs', function (Blueprint $table) {
                $table->string('ky_tuan', 20)->nullable()->after('ngay_trong_tuan')
                    ->comment('Kỳ tuần: all (mọi tuần), odd (tuần lẻ), even (tuần chẵn)');
            });
        }
    }
};

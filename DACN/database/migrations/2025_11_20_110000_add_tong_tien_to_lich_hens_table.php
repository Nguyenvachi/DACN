<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Parent: database/migrations/
     * Child: 2025_11_20_110000_add_tong_tien_to_lich_hens_table.php
     * Purpose: Thêm cột tong_tien vào lich_hens để lưu giá dịch vụ tại thời điểm đặt lịch
     */
    public function up(): void
    {
        if (Schema::hasTable('lich_hens')) {
            Schema::table('lich_hens', function (Blueprint $table) {
                if (!Schema::hasColumn('lich_hens', 'tong_tien')) {
                    $table->decimal('tong_tien', 12, 2)->default(0)->after('dich_vu_id');
                }
            });
        }
    }

    public function down(): void
    {
        // Giữ trống để tuân thủ "chỉ thêm, không bớt"
    }
};

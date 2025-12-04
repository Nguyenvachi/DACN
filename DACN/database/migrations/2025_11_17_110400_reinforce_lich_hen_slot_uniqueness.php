<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private function indexExists(string $table, string $indexName): bool
    {
        $db = DB::select('SELECT DATABASE() as db')[0]->db ?? null;
        if (!$db) return false;
        $sql = "SELECT COUNT(1) AS cnt FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND INDEX_NAME = ?";
        $res = DB::select($sql, [$db, $table, $indexName]);
        return ($res[0]->cnt ?? 0) > 0;
    }

    public function up(): void
    {
        Schema::table('lich_hens', function (Blueprint $table) {
            // Composite unique (already exists in prior migration with different name) – add only if missing
            // Existing name observed: 'lich_hens_unique_bacsi_ngay_gio' on (bac_si_id, ngay_hen, thoi_gian_hen)
        });

        // Helpful covering indexes
        if (!$this->indexExists('lich_hens', 'lich_hens_bacsi_ngay_idx')) {
            Schema::table('lich_hens', function (Blueprint $table) {
                $table->index(['bac_si_id', 'ngay_hen'], 'lich_hens_bacsi_ngay_idx');
            });
        }
        if (!$this->indexExists('lich_hens', 'lich_hens_user_ngay_idx')) {
            Schema::table('lich_hens', function (Blueprint $table) {
                $table->index(['user_id', 'ngay_hen'], 'lich_hens_user_ngay_idx');
            });
        }
        if (!$this->indexExists('lich_hens', 'lich_hens_status_idx')) {
            Schema::table('lich_hens', function (Blueprint $table) {
                $table->index(['trang_thai'], 'lich_hens_status_idx');
            });
        }
        if (!$this->indexExists('lich_hens', 'lich_hens_ngay_gio_idx')) {
            Schema::table('lich_hens', function (Blueprint $table) {
                $table->index(['ngay_hen', 'thoi_gian_hen'], 'lich_hens_ngay_gio_idx');
            });
        }
    }

    public function down(): void
    {
        // Chỉ drop các index được thêm bởi migration này nếu tồn tại
        foreach (['lich_hens_bacsi_ngay_idx','lich_hens_user_ngay_idx','lich_hens_status_idx','lich_hens_ngay_gio_idx'] as $idx) {
            if ($this->indexExists('lich_hens', $idx)) {
                Schema::table('lich_hens', function (Blueprint $table) use ($idx) {
                    $table->dropIndex($idx);
                });
            }
        }
    }
};

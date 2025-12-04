<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('lich_hens')) {
            return;
        }

        // Ưu tiên cột 'status'
        if (Schema::hasColumn('lich_hens', 'status')) {
            DB::table('lich_hens')->where('status', 'pending')->update(['status' => 'Chờ xác nhận']);
            DB::table('lich_hens')->whereIn('status', ['confirmed', 'approved'])->update(['status' => 'Đã xác nhận']);
            DB::table('lich_hens')->whereIn('status', ['canceled', 'cancelled'])->update(['status' => 'Đã hủy']);
            return;
        }

        // Fallback nếu dùng tên cột tiếng Việt
        foreach (['trang_thai', 'tinh_trang'] as $col) {
            if (Schema::hasColumn('lich_hens', $col)) {
                DB::table('lich_hens')->where($col, 'pending')->update([$col => 'Chờ xác nhận']);
                DB::table('lich_hens')->whereIn($col, ['confirmed', 'approved'])->update([$col => 'Đã xác nhận']);
                DB::table('lich_hens')->whereIn($col, ['canceled', 'cancelled'])->update([$col => 'Đã hủy']);
                return;
            }
        }
        // Nếu không có cột nào phù hợp thì bỏ qua (không làm gì)
    }

    public function down(): void
    {
        if (!Schema::hasTable('lich_hens')) {
            return;
        }

        if (Schema::hasColumn('lich_hens', 'status')) {
            DB::table('lich_hens')->where('status', 'Chờ xác nhận')->update(['status' => 'pending']);
            DB::table('lich_hens')->where('status', 'Đã xác nhận')->update(['status' => 'confirmed']);
            DB::table('lich_hens')->where('status', 'Đã hủy')->update(['status' => 'canceled']);
            return;
        }

        foreach (['trang_thai', 'tinh_trang'] as $col) {
            if (Schema::hasColumn('lich_hens', $col)) {
                DB::table('lich_hens')->where($col, 'Chờ xác nhận')->update([$col => 'pending']);
                DB::table('lich_hens')->where($col, 'Đã xác nhận')->update([$col => 'confirmed']);
                DB::table('lich_hens')->where($col, 'Đã hủy')->update([$col => 'canceled']);
                return;
            }
        }
    }
};

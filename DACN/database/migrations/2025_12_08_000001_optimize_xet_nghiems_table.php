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
        Schema::table('xet_nghiems', function (Blueprint $table) {
            // Xóa cột user_id (dư thừa - lấy qua benhAn->user_id)
            if (Schema::hasColumn('xet_nghiems', 'user_id')) {
                // Xóa foreign key trước nếu có
                try {
                    $table->dropForeign(['user_id']);
                } catch (\Exception $e) {
                    // Ignore if foreign key doesn't exist
                }
                $table->dropColumn('user_id');
            }

            // Xóa cột file_ket_qua (trùng lặp với file_path)
            if (Schema::hasColumn('xet_nghiems', 'file_ket_qua')) {
                $table->dropColumn('file_ket_qua');
            }

            // Đảm bảo có các cột cần thiết
            if (!Schema::hasColumn('xet_nghiems', 'nhan_xet')) {
                $table->text('nhan_xet')->nullable()->after('trang_thai');
            }

            if (!Schema::hasColumn('xet_nghiems', 'ket_qua')) {
                $table->text('ket_qua')->nullable()->after('nhan_xet');
            }

            // Thêm index để tối ưu query
            $table->index(['bac_si_id', 'trang_thai'], 'idx_xet_nghiem_bac_si_status');
            $table->index(['benh_an_id', 'created_at'], 'idx_xet_nghiem_benh_an_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('xet_nghiems', function (Blueprint $table) {
            $table->dropIndex('idx_xet_nghiem_bac_si_status');
            $table->dropIndex('idx_xet_nghiem_benh_an_time');

            // Khôi phục cột nếu cần rollback
            if (!Schema::hasColumn('xet_nghiems', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            }

            if (!Schema::hasColumn('xet_nghiems', 'file_ket_qua')) {
                $table->string('file_ket_qua')->nullable();
            }
        });
    }
};

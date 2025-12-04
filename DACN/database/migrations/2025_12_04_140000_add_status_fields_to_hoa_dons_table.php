<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Bổ sung các cột cho quản lý trạng thái hóa đơn nâng cao
     */
    public function up(): void
    {
        Schema::table('hoa_dons', function (Blueprint $table) {
            // Thêm cột mã hóa đơn chuẩn quốc tế
            if (!Schema::hasColumn('hoa_dons', 'ma_hoa_don')) {
                $table->string('ma_hoa_don')->unique()->nullable()->after('id');
            }

            // Thêm cột status chuẩn (paid, unpaid, partial, refunded, cancelled)
            if (!Schema::hasColumn('hoa_dons', 'status')) {
                $table->string('status')->default('unpaid')->after('trang_thai');
            }

            // Thêm cột số tiền đã thanh toán
            if (!Schema::hasColumn('hoa_dons', 'so_tien_da_thanh_toan')) {
                $table->decimal('so_tien_da_thanh_toan', 12, 2)->default(0)->after('tong_tien');
            }

            // Thêm cột số tiền còn lại
            if (!Schema::hasColumn('hoa_dons', 'so_tien_con_lai')) {
                $table->decimal('so_tien_con_lai', 12, 2)->default(0)->after('so_tien_da_thanh_toan');
            }

            // Thêm cột số tiền đã hoàn
            if (!Schema::hasColumn('hoa_dons', 'so_tien_da_hoan')) {
                $table->decimal('so_tien_da_hoan', 12, 2)->default(0)->after('so_tien_con_lai');
            }

            // Thêm index cho status và ma_hoa_don
            $table->index('status');
            $table->index('ma_hoa_don');
        });
    }

    /**
     * Reverse the migrations (tuân thủ quy tắc: chỉ thêm, không xóa)
     */
    public function down(): void
    {
        // Không xóa cột để tuân thủ quy tắc "chỉ thêm, không bớt"
    }
};

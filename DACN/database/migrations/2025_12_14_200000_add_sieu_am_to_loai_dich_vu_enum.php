<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Thêm 'sieu_am' vào ENUM loai_dich_vu trong bảng hoa_don_chi_tiets
        DB::statement("ALTER TABLE hoa_don_chi_tiets MODIFY COLUMN loai_dich_vu ENUM('thuoc', 'noi_soi', 'x_quang', 'xet_nghiem', 'dich_vu_kham', 'thu_thuat', 'sieu_am')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Xóa 'sieu_am' khỏi ENUM
        DB::statement("ALTER TABLE hoa_don_chi_tiets MODIFY COLUMN loai_dich_vu ENUM('thuoc', 'noi_soi', 'x_quang', 'xet_nghiem', 'dich_vu_kham', 'thu_thuat')");
    }
};

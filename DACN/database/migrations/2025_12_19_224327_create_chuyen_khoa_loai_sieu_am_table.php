<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Pivot table: ChuyenKhoa <-> LoaiSieuAm (File mẹ: database/migrations/2025_12_19_224327_create_chuyen_khoa_loai_sieu_am_table.php)
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('chuyen_khoa_loai_sieu_am')) {
            Schema::create('chuyen_khoa_loai_sieu_am', function (Blueprint $table) {
                $table->foreignId('chuyen_khoa_id')->constrained('chuyen_khoas')->cascadeOnDelete();
                $table->foreignId('loai_sieu_am_id')->constrained('loai_sieu_ams')->cascadeOnDelete();
                $table->primary(['chuyen_khoa_id', 'loai_sieu_am_id'], 'ck_lsa_pk');
            });
        }
    }

    /**
     * Reverse the migrations (File mẹ: database/migrations/2025_12_19_224327_create_chuyen_khoa_loai_sieu_am_table.php)
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chuyen_khoa_loai_sieu_am');
    }
};

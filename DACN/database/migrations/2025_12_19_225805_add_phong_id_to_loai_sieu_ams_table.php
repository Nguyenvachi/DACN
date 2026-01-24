<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Add phong_id to loai_sieu_ams (File mẹ: database/migrations/2025_12_19_225805_add_phong_id_to_loai_sieu_ams_table.php)
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loai_sieu_ams', function (Blueprint $table) {
            if (!Schema::hasColumn('loai_sieu_ams', 'phong_id')) {
                $table->foreignId('phong_id')->nullable()->after('thoi_gian_uoc_tinh')->constrained('phongs')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations (File mẹ: database/migrations/2025_12_19_225805_add_phong_id_to_loai_sieu_ams_table.php)
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loai_sieu_ams', function (Blueprint $table) {
            if (Schema::hasColumn('loai_sieu_ams', 'phong_id')) {
                $table->dropForeign(['phong_id']);
                $table->dropColumn('phong_id');
            }
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dich_vus', function (Blueprint $table) {
            // Chỉ thêm cột chuyen_khoa_id nếu chưa có
            if (!Schema::hasColumn('dich_vus', 'chuyen_khoa_id')) {
                $table->foreignId('chuyen_khoa_id')->nullable()->constrained('chuyen_khoas')->after('loai');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dich_vus', function (Blueprint $table) {
            if (Schema::hasColumn('dich_vus', 'chuyen_khoa_id')) {
                $table->dropForeign(['chuyen_khoa_id']);
                $table->dropColumn('chuyen_khoa_id');
            }
        });
    }
};

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
            $table->enum('loai', ['Cơ bản', 'Nâng cao'])->default('Cơ bản')->after('ten_dich_vu');
            $table->boolean('hoat_dong')->default(true)->after('thoi_gian_uoc_tinh'); // Trạng thái hoạt động
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
            $table->dropColumn(['loai', 'hoat_dong']);
        });
    }
};

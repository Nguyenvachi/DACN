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
        Schema::table('thuocs', function (Blueprint $table) {
            $table->integer('ton_toi_thieu')->nullable()->after('gia_tham_khao')->comment('Ngưỡng cảnh báo tồn kho thấp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('thuocs', function (Blueprint $table) {
            $table->dropColumn('ton_toi_thieu');
        });
    }
};

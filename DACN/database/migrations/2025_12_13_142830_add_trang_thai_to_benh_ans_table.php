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
        Schema::table('benh_ans', function (Blueprint $table) {
            $table->enum('trang_thai', ['Đang khám', 'Hoàn thành'])->default('Đang khám')->after('ghi_chu');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('benh_ans', function (Blueprint $table) {
            $table->dropColumn('trang_thai');
        });
    }
};

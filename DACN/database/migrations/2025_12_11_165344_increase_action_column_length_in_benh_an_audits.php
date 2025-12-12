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
        Schema::table('benh_an_audits', function (Blueprint $table) {
            // Tăng độ dài cột action từ 20 lên 100 ký tự
            $table->string('action', 100)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('benh_an_audits', function (Blueprint $table) {
            // Khôi phục lại độ dài cũ
            $table->string('action', 20)->change();
        });
    }
};

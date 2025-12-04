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
        Schema::table('benh_an_files', function (Blueprint $table) {
            // BỔ SUNG: cột disk để lưu tên disk (public hoặc benh_an_private)
            $table->string('disk', 50)->default('public')->after('path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('benh_an_files', function (Blueprint $table) {
            $table->dropColumn('disk');
        });
    }
};

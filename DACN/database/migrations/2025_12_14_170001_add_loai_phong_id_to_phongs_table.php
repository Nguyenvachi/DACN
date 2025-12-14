<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Thêm cột loai_phong_id vào bảng phongs
        Schema::table('phongs', function (Blueprint $table) {
            $table->unsignedBigInteger('loai_phong_id')->nullable()->after('ten');
            $table->foreign('loai_phong_id')->references('id')->on('loai_phongs')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('phongs', function (Blueprint $table) {
            $table->dropForeign(['loai_phong_id']);
            $table->dropColumn('loai_phong_id');
        });
    }
};

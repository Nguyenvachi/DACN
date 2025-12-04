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
        Schema::table('phieu_nhap_items', function (Blueprint $table) {
            // Xóa foreign key cũ
            $table->dropForeign(['thuoc_id']);

            // Thêm lại với restrict - không cho xóa thuốc nếu còn trong phiếu nhập
            $table->foreign('thuoc_id')
                  ->references('id')
                  ->on('thuocs')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('phieu_nhap_items', function (Blueprint $table) {
            $table->dropForeign(['thuoc_id']);

            $table->foreign('thuoc_id')
                  ->references('id')
                  ->on('thuocs');
        });
    }
};

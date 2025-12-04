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
        Schema::table('phieu_nhaps', function (Blueprint $table) {
            // Xóa foreign key cũ
            $table->dropForeign(['nha_cung_cap_id']);

            // Thêm lại với cascade
            $table->foreign('nha_cung_cap_id')
                  ->references('id')
                  ->on('nha_cung_caps')
                  ->onDelete('restrict'); // Không cho xóa NCC nếu còn phiếu nhập
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('phieu_nhaps', function (Blueprint $table) {
            $table->dropForeign(['nha_cung_cap_id']);

            $table->foreign('nha_cung_cap_id')
                  ->references('id')
                  ->on('nha_cung_caps');
        });
    }
};

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
        Schema::table('don_hangs', function (Blueprint $table) {
            $table->string('phuong_thuc_thanh_toan')->nullable()->after('trang_thai_thanh_toan');
            $table->timestamp('thanh_toan_at')->nullable()->after('phuong_thuc_thanh_toan');
        });
    }

    public function down()
    {
        Schema::table('don_hangs', function (Blueprint $table) {
            $table->dropColumn(['phuong_thuc_thanh_toan', 'thanh_toan_at']);
        });
    }
};

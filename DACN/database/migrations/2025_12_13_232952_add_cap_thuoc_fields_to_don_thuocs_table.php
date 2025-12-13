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
        Schema::table('don_thuocs', function (Blueprint $table) {
            $table->timestamp('ngay_cap_thuoc')->nullable()->after('ghi_chu');
            $table->foreignId('nguoi_cap_thuoc_id')->nullable()->after('ngay_cap_thuoc')->constrained('users')->onDelete('set null');
            $table->text('ghi_chu_cap_thuoc')->nullable()->after('nguoi_cap_thuoc_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('don_thuocs', function (Blueprint $table) {
            $table->dropForeign(['nguoi_cap_thuoc_id']);
            $table->dropColumn(['ngay_cap_thuoc', 'nguoi_cap_thuoc_id', 'ghi_chu_cap_thuoc']);
        });
    }
};

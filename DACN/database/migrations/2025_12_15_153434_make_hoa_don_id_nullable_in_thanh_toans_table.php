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
        Schema::table('thanh_toans', function (Blueprint $table) {
            $table->foreignId('hoa_don_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('thanh_toans', function (Blueprint $table) {
            $table->foreignId('hoa_don_id')->nullable(false)->change();
        });
    }
};

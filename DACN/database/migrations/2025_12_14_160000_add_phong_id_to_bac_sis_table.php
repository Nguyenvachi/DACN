<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bac_sis', function (Blueprint $table) {
            // Add phong_id foreign key
            $table->unsignedBigInteger('phong_id')->nullable()->after('so_phong');
            $table->foreign('phong_id')->references('id')->on('phongs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bac_sis', function (Blueprint $table) {
            $table->dropForeign(['phong_id']);
            $table->dropColumn('phong_id');
        });
    }
};

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
            $table->string('so_phong', 20)->nullable()->after('trang_thai')->comment('Số phòng khám của bác sĩ');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bac_sis', function (Blueprint $table) {
            $table->dropColumn('so_phong');
        });
    }
};

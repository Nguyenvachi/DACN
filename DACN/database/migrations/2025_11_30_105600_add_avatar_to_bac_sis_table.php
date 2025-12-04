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
    public function up(): void
    {
        if (Schema::hasTable('bac_sis')) {
            Schema::table('bac_sis', function (Blueprint $table) {
                if (!Schema::hasColumn('bac_sis', 'avatar')) {
                    $table->string('avatar')->nullable()->after('so_dien_thoai');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        if (Schema::hasTable('bac_sis')) {
            Schema::table('bac_sis', function (Blueprint $table) {
                if (Schema::hasColumn('bac_sis', 'avatar')) {
                    $table->dropColumn('avatar');
                }
            });
        }
    }
};

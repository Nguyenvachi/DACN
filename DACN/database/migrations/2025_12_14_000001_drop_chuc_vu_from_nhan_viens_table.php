<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('nhan_viens', function (Blueprint $table) {
            if (Schema::hasColumn('nhan_viens', 'chuc_vu')) {
                $table->dropColumn('chuc_vu');
            }
        });
    }

    public function down(): void
    {
        Schema::table('nhan_viens', function (Blueprint $table) {
            if (!Schema::hasColumn('nhan_viens', 'chuc_vu')) {
                $table->string('chuc_vu')->nullable();
            }
        });
    }
};

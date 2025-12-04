<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('nhan_viens', function (Blueprint $table) {
            if (Schema::hasColumn('nhan_viens','user_id')) {
                // THÊM UNIQUE (NULL vẫn cho phép)
                $table->unique('user_id','nhan_viens_user_id_unique');
            }
        });
    }

    public function down(): void {
        Schema::table('nhan_viens', function (Blueprint $table) {
            $table->dropUnique('nhan_viens_user_id_unique');
        });
    }
};
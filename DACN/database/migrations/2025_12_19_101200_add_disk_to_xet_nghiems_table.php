<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('xet_nghiems')) {
            Schema::table('xet_nghiems', function (Blueprint $table) {
                if (!Schema::hasColumn('xet_nghiems', 'disk')) {
                    $table->string('disk')->nullable()->after('file_path')->comment('Disk lưu file (public|benh_an_private)');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('xet_nghiems')) {
            Schema::table('xet_nghiems', function (Blueprint $table) {
                if (Schema::hasColumn('xet_nghiems', 'disk')) {
                    $table->dropColumn('disk');
                }
            });
        }
    }
};

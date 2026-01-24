<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('xet_nghiems') || !Schema::hasColumn('xet_nghiems', 'file_path')) {
            return;
        }

        // MySQL/MariaDB: allow pending test requests without uploaded file yet.
        DB::statement("ALTER TABLE `xet_nghiems` MODIFY `file_path` VARCHAR(255) NULL");

        // Some environments may have disk as NOT NULL without default.
        if (Schema::hasColumn('xet_nghiems', 'disk')) {
            DB::statement("ALTER TABLE `xet_nghiems` MODIFY `disk` VARCHAR(255) NULL");
        }
    }

    public function down(): void
    {
        // No-op: making file_path NOT NULL again can break existing rows that already have NULL.
    }
};

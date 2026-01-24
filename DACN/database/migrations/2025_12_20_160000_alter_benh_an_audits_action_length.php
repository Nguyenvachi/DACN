<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('benh_an_audits') || !Schema::hasColumn('benh_an_audits', 'action')) {
            return;
        }

        $driver = DB::getDriverName();

        try {
            if ($driver === 'mysql') {
                DB::statement("ALTER TABLE benh_an_audits MODIFY action VARCHAR(100) NOT NULL");
            } elseif ($driver === 'pgsql') {
                DB::statement("ALTER TABLE benh_an_audits ALTER COLUMN action TYPE VARCHAR(100)");
            } elseif ($driver === 'sqlsrv') {
                DB::statement("ALTER TABLE benh_an_audits ALTER COLUMN action VARCHAR(100) NOT NULL");
            } else {
                // sqlite or unknown driver: skip non-destructive migration
                // (SQLite would require table rebuild to change column size)
            }
        } catch (\Throwable $e) {
            // Non-blocking: keep app running even if ALTER fails in some environments.
        }
    }

    public function down(): void
    {
        // Intentionally left blank (non-destructive)
    }
};

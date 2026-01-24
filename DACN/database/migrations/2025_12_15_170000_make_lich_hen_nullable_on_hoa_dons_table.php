<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Use raw SQL to avoid requiring doctrine/dbal for change()
        DB::statement('ALTER TABLE `hoa_dons` MODIFY `lich_hen_id` bigint unsigned NULL');
    }

    public function down()
    {
        // Revert to NOT NULL (may fail if NULL values exist)
        DB::statement('ALTER TABLE `hoa_dons` MODIFY `lich_hen_id` bigint unsigned NOT NULL');
    }
};

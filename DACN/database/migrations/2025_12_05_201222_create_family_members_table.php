<?php

// Migration for family_members retained for history but marked as deprecated.
// The family module was removed from application code. Keep this file for
// DB history; if you want to remove the table from the database, run a
// dedicated migration to drop `family_members` after verifying no data is needed.

return new class extends \Illuminate\Database\Migrations\Migration
{
    public function up()
    {
        // intentionally left blank - migration retained as historical reference.
    }

    public function down()
    {
        // intentionally left blank
    }
};

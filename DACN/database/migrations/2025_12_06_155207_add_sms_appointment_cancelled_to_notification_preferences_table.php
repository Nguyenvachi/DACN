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
    public function up()
    {
        if (!Schema::hasColumn('notification_preferences', 'sms_appointment_cancelled')) {
            Schema::table('notification_preferences', function (Blueprint $table) {
                $table->boolean('sms_appointment_cancelled')->default(false)->after('sms_appointment_confirmed');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notification_preferences', function (Blueprint $table) {
            $table->dropColumn('sms_appointment_cancelled');
        });
    }
};

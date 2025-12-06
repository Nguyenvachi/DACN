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
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Email notifications
            $table->boolean('email_appointment_reminder')->default(true);
            $table->boolean('email_appointment_confirmed')->default(true);
            $table->boolean('email_appointment_cancelled')->default(true);
            $table->boolean('email_test_results')->default(true);
            $table->boolean('email_promotions')->default(false);

            // SMS notifications (nếu có tích hợp SMS)
            $table->boolean('sms_appointment_reminder')->default(false);
            $table->boolean('sms_appointment_confirmed')->default(false);
            $table->boolean('sms_appointment_cancelled')->default(false);

            // Thời gian nhắc trước
            $table->integer('reminder_hours_before')->default(24)->comment('Nhắc trước bao nhiêu giờ');

            $table->timestamps();

            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_preferences');
    }
};

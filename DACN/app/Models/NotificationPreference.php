<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Parent file: app/Models/NotificationPreference.php
 */
class NotificationPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email_appointment_reminder',
        'email_appointment_confirmed',
        'email_appointment_cancelled',
        'email_test_results',
        'email_promotions',
        'sms_appointment_reminder',
        'sms_appointment_confirmed',
        'sms_appointment_cancelled',
        'reminder_hours_before',
    ];

    protected $casts = [
        'email_appointment_reminder' => 'boolean',
        'email_appointment_confirmed' => 'boolean',
        'email_appointment_cancelled' => 'boolean',
        'email_test_results' => 'boolean',
        'email_promotions' => 'boolean',
        'sms_appointment_reminder' => 'boolean',
        'sms_appointment_confirmed' => 'boolean',
        'sms_appointment_cancelled' => 'boolean',
        'reminder_hours_before' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

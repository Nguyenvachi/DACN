<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;

class NotificationPolicy
{
    /**
     * Determine whether the user can view the notification.
     */
    public function view(User $user, DatabaseNotification $notification): bool
    {
        return $notification->notifiable_id === $user->id;
    }

    /**
     * Determine whether the user can update the notification.
     */
    public function update(User $user, DatabaseNotification $notification): bool
    {
        return $notification->notifiable_id === $user->id;
    }
}

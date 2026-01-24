<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomNotification extends Notification
{
    public string $title;
    public string $message;
    public ?string $action_url;

    public function __construct(string $title, string $message = '', ?string $action_url = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->action_url = $action_url;
    }

    public function via($notifiable)
    {
        return ['database']; // Chỉ lưu DB, không mail
    }

    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'action_url' => $this->action_url,
        ];
    }
}

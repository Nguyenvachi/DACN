<?php

namespace App\Notifications;

use App\Models\SieuAm;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MedicalUltrasoundRequested extends Notification
{
    use Queueable;

    public function __construct(
        public SieuAm $sieuAm,
        public string $title,
        public string $message,
        public ?string $actionUrl = null
    ) {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'action_url' => $this->actionUrl,
            'meta' => [
                'module' => 'sieu_am',
                'sieu_am_id' => $this->sieuAm->id,
            ],
        ];
    }
}

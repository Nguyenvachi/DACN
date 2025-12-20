<?php

namespace App\Notifications;

use App\Models\TheoDoiThaiKy;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PregnancyTrackingStatusUpdated extends Notification
{
    use Queueable;

    public function __construct(
        public TheoDoiThaiKy $record,
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
                'module' => 'theo_doi_thai_ky',
                'theo_doi_thai_ky_id' => $this->record->id,
                'benh_an_id' => $this->record->benh_an_id,
                'trang_thai' => $this->record->trang_thai,
            ],
        ];
    }
}

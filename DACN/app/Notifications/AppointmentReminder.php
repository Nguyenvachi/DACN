<?php

namespace App\Notifications;

use App\Models\LichHen;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public $shouldQueue = true;

    public function __construct(public LichHen $lichHen, public string $windowLabel, bool $sync = false)
    {
        // $windowLabel ví dụ: '24h' hoặc '3h'
        if ($sync) {
            $this->shouldQueue = false;
        }
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $lh = $this->lichHen;
        $doctor = optional($lh->bacSi)->ho_ten ?? 'Bác sĩ';
        $service = optional($lh->dichVu)->ten ?? 'Dịch vụ khám';
        $date = $lh->ngay_hen;
        $time = $lh->thoi_gian_hen;

        $url = url('/lich-hen-cua-toi');
        $title = "Nhắc lịch khám (T-{$this->windowLabel})";

        return (new MailMessage)
            ->subject($title)
            ->greeting('Xin chào,')
            ->line("Bạn có lịch khám sắp tới với {$doctor}.")
            ->line("Dịch vụ: {$service}")
            ->line("Thời gian: {$date} {$time}")
            ->action('Xem lịch hẹn của tôi', $url)
            ->line('Nếu bạn cần thay đổi lịch, vui lòng thao tác sớm để đảm bảo khung giờ.');
    }
}

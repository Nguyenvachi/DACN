<?php

namespace App\Mail;

use App\Models\LichHen;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Parent: app/Mail/
 * Child: LichHenCheckIn.php
 * Purpose: Email thông báo bệnh nhân đã check-in thành công
 */
class LichHenCheckIn extends Mailable
{
    use Queueable, SerializesModels;

    public $lichHen;

    public function __construct(LichHen $lichHen)
    {
        $this->lichHen = $lichHen;
    }

    public function build()
    {
        return $this->subject('Lịch hẹn #' . $this->lichHen->id . ' - Bạn đã check-in thành công')
                    ->view('emails.lich_hen_check_in');
    }
}

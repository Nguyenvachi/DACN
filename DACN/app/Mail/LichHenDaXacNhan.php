<?php

namespace App\Mail;

use App\Models\LichHen;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Parent: app/Mail/
 * Child: LichHenDaXacNhan.php
 * Purpose: Email thông báo lịch hẹn đã được xác nhận
 */
class LichHenDaXacNhan extends Mailable
{
    use Queueable, SerializesModels;

    public $lichHen;

    public function __construct(LichHen $lichHen)
    {
        $this->lichHen = $lichHen;
    }

    public function build()
    {
        return $this->subject('Lịch hẹn #' . $this->lichHen->id . ' đã được xác nhận')
                    ->view('emails.lich_hen_xac_nhan');
    }
}

<?php

namespace App\Mail;

use App\Models\LichHen;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Parent: app/Mail/
 * Child: LichHenBiHuy.php
 * Purpose: Email thông báo lịch hẹn bị hủy
 */
class LichHenBiHuy extends Mailable
{
    use Queueable, SerializesModels;

    public $lichHen;
    public $reason;

    public function __construct(LichHen $lichHen, string $reason = '')
    {
        $this->lichHen = $lichHen;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('Lịch hẹn #' . $this->lichHen->id . ' đã bị hủy')
                    ->view('emails.lich_hen_bi_huy');
    }
}

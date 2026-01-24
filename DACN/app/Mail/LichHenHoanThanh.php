<?php

namespace App\Mail;

use App\Models\LichHen;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Parent: app/Mail/
 * Child: LichHenHoanThanh.php
 * Purpose: Email tóm tắt sau khi khám hoàn thành
 */
class LichHenHoanThanh extends Mailable
{
    use Queueable, SerializesModels;

    public $lichHen;

    public function __construct(LichHen $lichHen)
    {
        $this->lichHen = $lichHen;
    }

    public function build()
    {
        return $this->subject('Lịch hẹn #' . $this->lichHen->id . ' - Khám hoàn thành')
                    ->view('emails.lich_hen_hoan_thanh');
    }
}

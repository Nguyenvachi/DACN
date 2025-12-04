<?php

namespace App\Mail;

use App\Models\HoaDon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HoaDonDaThanhToan extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public HoaDon $hoaDon) {}

    public function build()
    {
        return $this->subject('Biên lai thanh toán #' . $this->hoaDon->id)
            ->view('emails.hoadon_paid', ['hoaDon' => $this->hoaDon]);
    }
}

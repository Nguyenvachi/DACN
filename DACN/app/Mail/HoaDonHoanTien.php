<?php

namespace App\Mail;

use App\Models\HoaDon;
use App\Models\HoanTien;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HoaDonHoanTien extends Mailable
{
    use Queueable, SerializesModels;

    public $hoaDon;
    public $hoanTien;

    /**
     * Create a new message instance.
     */
    public function __construct(HoaDon $hoaDon, HoanTien $hoanTien)
    {
        $this->hoaDon = $hoaDon;
        $this->hoanTien = $hoanTien;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thông báo hoàn tiền - Hóa đơn #' . $this->hoaDon->ma_hoa_don,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.hoa_don_hoan_tien',
            with: [
                'hoaDon' => $this->hoaDon,
                'hoanTien' => $this->hoanTien,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

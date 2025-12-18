<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Booking $booking)
    {
    }

    public function build()
    {
        return $this->subject('رقم الحجز الخاص بك')
            ->view('emails.booking_code')
            ->with([
                'booking' => $this->booking,
            ]);
    }
}



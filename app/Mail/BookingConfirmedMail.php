<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingConfirmedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Booking $booking)
    {
    }

    public function build()
    {
        return $this->subject('تم تأكيد حجزك بنجاح')
            ->view('emails.booking_confirmed')
            ->with([
                'booking' => $this->booking,
            ]);
    }
}



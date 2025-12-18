<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $senderName,
        public string $senderEmail,
        public string $messageBody,
    ) {}

    public function build()
    {
        return $this->subject('رسالة تواصل جديدة من الموقع')
            ->replyTo($this->senderEmail, $this->senderName)
            ->view('emails.contact_message')
            ->with([
                'senderName' => $this->senderName,
                'senderEmail' => $this->senderEmail,
                'messageBody' => $this->messageBody,
            ]);
    }
}



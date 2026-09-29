<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentSignupOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $otpCode,
        public string $studentName,
        public int $expiryMinutes = 15
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'HITAM Hostels — Your 6-Digit Portal Verification Code: ' . $this->otpCode,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.student_otp',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $otpCode,
        public string $userName,
        public int $expiryMinutes = 15
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'HITAM Hostels — Your Password Reset OTP Code: ' . $this->otpCode,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.password_reset_otp',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

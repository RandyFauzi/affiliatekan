<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordOtpMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $plainOtpCode
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode OTP Reset Password Affiliatekan',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.auth.reset-password-otp',
            with: [
                'plainOtpCode' => $this->plainOtpCode,
            ],
        );
    }
}

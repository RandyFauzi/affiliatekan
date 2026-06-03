<?php

namespace App\Mail;

use App\Models\Payout;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PayoutCompletedForAffiliate extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly Payout $payout,
        public readonly string $proofUrl
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Affiliatekan: Payout Anda telah dibayarkan'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.payouts.completed-for-affiliate',
        );
    }
}

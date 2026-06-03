<?php

namespace App\Mail;

use App\Models\Payout;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PayoutRequestedForVendor extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly Payout $payout
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Affiliatekan: Permintaan payout baru dari afiliator'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.payouts.requested-for-vendor',
        );
    }
}

<?php

namespace App\Jobs;

use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendVendorWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        protected Vendor $vendor,
        protected string $event,
        protected array $data
    ) {
    }

    public function handle(): void
    {
        $webhookUrl = $this->vendor->webhook_url;

        if (blank($webhookUrl)) {
            return;
        }

        $payload = [
            'event' => $this->event,
            'timestamp' => now()->toIso8601String(),
            'data' => $this->data,
        ];

        $jsonPayload = json_encode($payload);
        $signature = hash_hmac('sha256', $jsonPayload, $this->vendor->api_key);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'X-Affiliatekan-Signature' => $signature,
        ])->post($webhookUrl, $payload);

        if ($response->failed()) {
            Log::warning('Webhook delivery failed.', [
                'vendor_id' => $this->vendor->id,
                'webhook_url' => $webhookUrl,
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            throw new \RuntimeException("Webhook failed with status " . $response->status());
        }
    }
}

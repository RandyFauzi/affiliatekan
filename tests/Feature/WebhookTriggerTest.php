<?php

namespace Tests\Feature;

use App\Jobs\SendVendorWebhookJob;
use App\Models\Affiliate;
use App\Models\Payout;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class WebhookTriggerTest extends TestCase
{
    use RefreshDatabase;

    public function test_conversion_creation_triggers_webhook_if_vendor_has_webhook_url(): void
    {
        Queue::fake();

        $vendor = Vendor::factory()->create([
            'webhook_url' => 'https://vendor-webhook.test/endpoints',
        ]);
        $affiliate = Affiliate::factory()->create();

        $response = $this
            ->actingAs($vendor->user)
            ->withHeader('X-API-KEY', $vendor->api_key)
            ->postJson('/api/v1/conversions', [
                'affiliate_code' => $affiliate->referral_code,
                'vendor_order_id' => 'ORDER-WEBHOOK-001',
                'sale_amount' => 100000,
            ]);

        $response->assertStatus(201);

        Queue::assertPushed(SendVendorWebhookJob::class);
    }

    public function test_payout_transitions_trigger_webhook_if_vendor_has_webhook_url(): void
    {
        Queue::fake();

        $vendor = Vendor::factory()->create([
            'webhook_url' => 'https://vendor-webhook.test/endpoints',
        ]);
        $affiliate = Affiliate::factory()->create();

        $requestedPayout = Payout::query()->create([
            'affiliate_id' => $affiliate->id,
            'vendor_id' => $vendor->id,
            'amount' => 50000,
            'status' => 'requested',
        ]);

        $this
            ->actingAs($vendor->user)
            ->post(route('vendor.payouts.process', $requestedPayout->id));

        Queue::assertPushed(SendVendorWebhookJob::class);

        $this
            ->actingAs($vendor->user)
            ->post(route('vendor.payouts.pay-manual', $requestedPayout->id), [
                'proof_of_transfer_path' => UploadedFile::fake()->image('proof.png'),
            ]);

        Queue::assertPushed(SendVendorWebhookJob::class);
    }

    public function test_send_vendor_webhook_job_sends_http_request_with_correct_signature(): void
    {
        Http::fake();

        $vendor = Vendor::factory()->create([
            'webhook_url' => 'https://vendor-webhook.test/endpoints',
        ]);

        $job = new SendVendorWebhookJob($vendor, 'test.event', ['foo' => 'bar']);
        $job->handle();

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) use ($vendor): bool {
            $this->assertSame('https://vendor-webhook.test/endpoints', $request->url());
            $this->assertTrue($request->hasHeader('X-Affiliatekan-Signature'));
            
            $signature = $request->header('X-Affiliatekan-Signature')[0];
            $calculated = hash_hmac('sha256', json_encode($request->data()), $vendor->api_key);
            
            return $signature === $calculated && $request['event'] === 'test.event';
        });
    }
}

<?php

namespace Tests\Feature;

use App\Models\Affiliate;
use App\Models\Conversion;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConversionWebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_conversion_calculates_flat_commission_correctly(): void
    {
        $vendor = Vendor::factory()->flatCommission(25000)->create();
        $affiliate = Affiliate::factory()->create();

        $response = $this
            ->withHeader('X-API-KEY', $vendor->api_key)
            ->postJson('/api/v1/conversions', [
                'affiliate_code' => $affiliate->referral_code,
                'vendor_order_id' => 'ORDER-FLAT-001',
                'sale_amount' => 100000,
            ]);

        $response->assertCreated();

        $this->assertDatabaseHas('conversions', [
            'vendor_id' => $vendor->id,
            'affiliate_id' => $affiliate->id,
            'vendor_order_id' => 'ORDER-FLAT-001',
            'sale_amount' => '100000.00',
            'commission_amount' => '25000.00',
            'status' => 'pending',
        ]);
    }

    public function test_conversion_calculates_percentage_commission_correctly(): void
    {
        $vendor = Vendor::factory()->percentageCommission(10)->create();
        $affiliate = Affiliate::factory()->create();

        $response = $this
            ->withHeader('X-API-KEY', $vendor->api_key)
            ->postJson('/api/v1/conversions', [
                'affiliate_code' => $affiliate->referral_code,
                'vendor_order_id' => 'ORDER-PERCENT-001',
                'sale_amount' => 100000,
            ]);

        $response->assertCreated();

        $this->assertDatabaseHas('conversions', [
            'vendor_id' => $vendor->id,
            'affiliate_id' => $affiliate->id,
            'vendor_order_id' => 'ORDER-PERCENT-001',
            'sale_amount' => '100000.00',
            'commission_amount' => '10000.00',
            'status' => 'pending',
        ]);
    }

    public function test_conversion_idempotency_prevents_duplicate_commissions(): void
    {
        $vendor = Vendor::factory()->percentageCommission(12)->create();
        $affiliate = Affiliate::factory()->create();

        $payload = [
            'affiliate_code' => $affiliate->referral_code,
            'vendor_order_id' => 'ORDER-IDEMPOTENT-001',
            'sale_amount' => 125000,
        ];

        $this
            ->withHeader('X-API-KEY', $vendor->api_key)
            ->postJson('/api/v1/conversions', $payload)
            ->assertCreated();

        $this
            ->withHeader('X-API-KEY', $vendor->api_key)
            ->postJson('/api/v1/conversions', $payload)
            ->assertCreated();

        $this->assertDatabaseCount('conversions', 1);

        $conversion = Conversion::query()
            ->where('vendor_id', $vendor->id)
            ->where('vendor_order_id', 'ORDER-IDEMPOTENT-001')
            ->first();

        $this->assertNotNull($conversion);
        $this->assertSame('15000.00', $conversion->commission_amount);
    }
}

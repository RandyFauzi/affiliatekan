<?php

namespace Tests\Feature;

use App\Models\Affiliate;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClickIngestionTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_click_recording(): void
    {
        $vendor = Vendor::factory()->create([
            'website_url' => 'https://toko-ikan.test',
            'cookie_duration_days' => 45,
        ]);
        $affiliate = Affiliate::factory()->create();

        $response = $this
            ->withHeader('Origin', 'https://toko-ikan.test')
            ->withHeader('Referer', 'https://toko-ikan.test/produk/kakap-premium?ref=' . $affiliate->referral_code)
            ->withServerVariables([
                'REMOTE_ADDR' => '203.0.113.10',
                'HTTP_USER_AGENT' => 'AffiliatekanClickTest/1.0',
            ])
            ->postJson('/api/v1/clicks', [
                'affiliate_code' => $affiliate->referral_code,
            ]);

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'cookie_duration_days' => 45,
            ]);

        $this->assertDatabaseHas('click_logs', [
            'affiliate_id' => $affiliate->id,
            'vendor_id' => $vendor->id,
            'ip_address' => '203.0.113.10',
            'user_agent' => 'AffiliatekanClickTest/1.0',
        ]);
    }

    public function test_click_spam_prevention(): void
    {
        $vendor = Vendor::factory()->create([
            'website_url' => 'https://langitara.test',
        ]);
        $affiliate = Affiliate::factory()->create();

        $serverVariables = [
            'REMOTE_ADDR' => '203.0.113.20',
            'HTTP_USER_AGENT' => 'AffiliatekanClickSpamTest/1.0',
        ];

        $this
            ->withHeader('Origin', 'https://langitara.test')
            ->withHeader('Referer', 'https://langitara.test/checkout?ref=' . $affiliate->referral_code)
            ->withServerVariables($serverVariables)
            ->postJson('/api/v1/clicks', [
                'affiliate_code' => $affiliate->referral_code,
            ])
            ->assertOk();

        $this
            ->withHeader('Origin', 'https://langitara.test')
            ->withHeader('Referer', 'https://langitara.test/checkout?ref=' . $affiliate->referral_code)
            ->withServerVariables($serverVariables)
            ->postJson('/api/v1/clicks', [
                'affiliate_code' => $affiliate->referral_code,
            ])
            ->assertOk();

        $this->assertDatabaseCount('click_logs', 1);
    }

    public function test_click_fails_when_vendor_origin_cannot_be_resolved(): void
    {
        $affiliate = Affiliate::factory()->create();

        $response = $this
            ->withHeader('Origin', 'https://unknown-vendor.test')
            ->postJson('/api/v1/clicks', [
                'affiliate_code' => $affiliate->referral_code,
            ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Vendor website could not be resolved from this tracking request.',
            ]);

        $this->assertDatabaseCount('click_logs', 0);
    }
}

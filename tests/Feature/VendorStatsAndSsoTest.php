<?php

namespace Tests\Feature;

use App\Models\Affiliate;
use App\Models\ClickLog;
use App\Models\Conversion;
use App\Models\Payout;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class VendorStatsAndSsoTest extends TestCase
{
    use RefreshDatabase;

    public function test_stats_endpoint_requires_api_key(): void
    {
        $response = $this->getJson('/api/v1/vendor/stats');
        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Unauthorized.',
            ]);
    }

    public function test_stats_endpoint_returns_accurate_metrics(): void
    {
        $user = User::factory()->create(['role' => 'vendor']);
        $vendor = Vendor::factory()->create([
            'user_id' => $user->id,
            'api_key' => 'vnd_test_api_key_12345',
        ]);

        $affiliate = Affiliate::factory()->create();

        // Seed 3 Clicks
        ClickLog::create([
            'affiliate_id' => $affiliate->id,
            'vendor_id' => $vendor->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Test',
            'referer_url' => 'https://test.com',
            'clicked_at' => now(),
        ]);
        ClickLog::create([
            'affiliate_id' => $affiliate->id,
            'vendor_id' => $vendor->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Test',
            'referer_url' => 'https://test.com',
            'clicked_at' => now(),
        ]);
        ClickLog::create([
            'affiliate_id' => $affiliate->id,
            'vendor_id' => $vendor->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Test',
            'referer_url' => 'https://test.com',
            'clicked_at' => now(),
        ]);

        // Seed 2 Conversions (1 approved, 1 paid, 1 pending)
        Conversion::create([
            'vendor_id' => $vendor->id,
            'affiliate_id' => $affiliate->id,
            'vendor_order_id' => 'ORD1',
            'sale_amount' => 100000,
            'commission_amount' => 10000,
            'status' => 'approved',
        ]);
        Conversion::create([
            'vendor_id' => $vendor->id,
            'affiliate_id' => $affiliate->id,
            'vendor_order_id' => 'ORD2',
            'sale_amount' => 200000,
            'commission_amount' => 20000,
            'status' => 'paid',
        ]);
        Conversion::create([
            'vendor_id' => $vendor->id,
            'affiliate_id' => $affiliate->id,
            'vendor_order_id' => 'ORD3',
            'sale_amount' => 150000,
            'commission_amount' => 15000,
            'status' => 'pending',
        ]);

        // Seed 1 Payout in requested status
        Payout::create([
            'affiliate_id' => $affiliate->id,
            'vendor_id' => $vendor->id,
            'amount' => 5000.00,
            'status' => 'requested',
        ]);

        $response = $this->withHeader('X-API-KEY', 'vnd_test_api_key_12345')
            ->getJson('/api/v1/vendor/stats');

        $response->assertOk()
            ->assertJson([
                'total_clicks' => 3,
                'total_conversions' => 2, // only approved and paid
                'total_commissions' => 30000.00, // conversion 1 + conversion 2
                'pending_payouts' => 5000.00,
            ]);
    }

    public function test_sso_ticket_endpoint_requires_api_key(): void
    {
        $response = $this->postJson('/api/v1/vendor/sso-ticket');
        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Unauthorized.',
            ]);
    }

    public function test_sso_ticket_endpoint_generates_valid_ticket_and_authenticates(): void
    {
        $user = User::factory()->create(['role' => 'vendor']);
        $vendor = Vendor::factory()->create([
            'user_id' => $user->id,
            'api_key' => 'vnd_test_api_key_sso',
        ]);

        $response = $this->withHeader('X-API-KEY', 'vnd_test_api_key_sso')
            ->postJson('/api/v1/vendor/sso-ticket');

        $response->assertOk();
        $this->assertArrayHasKey('sso_url', $response->json());

        $ssoUrl = $response->json()['sso_url'];
        $urlParts = parse_url($ssoUrl);
        parse_str($urlParts['query'], $queryParams);
        $token = $queryParams['token'];

        $this->assertNotEmpty($token);
        $this->assertEquals($vendor->id, Cache::get('sso_token_' . $token));

        // Use the SSO link to authenticate
        $verifyResponse = $this->get('/sso-login?token=' . $token);

        // Assert it redirects to vendor dashboard
        $verifyResponse->assertRedirect(route('vendor.dashboard.index'));

        // Assert user is logged in
        $this->assertTrue(auth()->check());
        $this->assertEquals($user->id, auth()->id());

        // Assert cache token is burned on read
        $this->assertNull(Cache::get('sso_token_' . $token));
    }

    public function test_sso_verify_rejects_invalid_token(): void
    {
        $response = $this->get('/sso-login?token=invalid_or_expired_token');
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('sso');
    }
}

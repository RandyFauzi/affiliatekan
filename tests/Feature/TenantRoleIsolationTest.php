<?php

namespace Tests\Feature;

use App\Models\Affiliate;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantRoleIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_affiliate_cannot_access_vendor_routes(): void
    {
        $affiliateUser = User::factory()->affiliate()->create();
        Affiliate::factory()->create([
            'user_id' => $affiliateUser->id,
        ]);

        $this->actingAs($affiliateUser)
            ->get('/tenant/integration')
            ->assertRedirect('/');

        $this->actingAs($affiliateUser)
            ->get('/tenant/payouts')
            ->assertRedirect('/');

    }

    public function test_vendor_cannot_access_affiliate_routes(): void
    {
        $vendorUser = User::factory()->vendor()->create();
        Vendor::factory()->create([
            'user_id' => $vendorUser->id,
        ]);

        $this->actingAs($vendorUser)
            ->get('/affiliate/dashboard')
            ->assertRedirect('/');
    }
}

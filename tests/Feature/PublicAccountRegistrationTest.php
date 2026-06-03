<?php

namespace Tests\Feature;

use App\Models\Affiliate;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicAccountRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_register_as_affiliate(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'New Affiliate',
            'email' => 'new-affiliate@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'affiliate',
        ]);

        $registeredUser = User::query()->where('email', 'new-affiliate@example.com')->firstOrFail();

        $response->assertRedirect(route('affiliate.dashboard.index'));
        $this->assertAuthenticatedAs($registeredUser);
        $this->assertDatabaseHas('users', [
            'email' => 'new-affiliate@example.com',
            'role' => 'affiliate',
        ]);
        $this->assertTrue(Affiliate::query()
            ->where('user_id', $registeredUser->id)
            ->where('referral_code', 'like', 'aff_%')
            ->exists());
    }

    public function test_guest_can_register_as_vendor(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'New Vendor',
            'email' => 'new-vendor@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'vendor',
            'company_name' => 'Vendor Baru',
            'website_url' => 'https://vendor-baru.test',
        ]);

        $registeredUser = User::query()->where('email', 'new-vendor@example.com')->firstOrFail();

        $response->assertRedirect(route('vendor.dashboard.index'));
        $this->assertAuthenticatedAs($registeredUser);
        $this->assertDatabaseHas('users', [
            'email' => 'new-vendor@example.com',
            'role' => 'vendor',
        ]);
        $this->assertTrue(Vendor::query()
            ->where('user_id', $registeredUser->id)
            ->where('company_name', 'Vendor Baru')
            ->where('commission_type', 'percentage')
            ->where('commission_value', 10)
            ->whereNotNull('api_key')
            ->exists());
    }
}

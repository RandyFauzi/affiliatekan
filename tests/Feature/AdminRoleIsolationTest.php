<?php

namespace Tests\Feature;

use App\Models\Affiliate;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRoleIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get('/admin/dashboard')
            ->assertOk();
    }

    public function test_vendor_cannot_access_admin_routes(): void
    {
        $vendorUser = User::factory()->vendor()->create();
        Vendor::factory()->create([
            'user_id' => $vendorUser->id,
        ]);

        $this->actingAs($vendorUser)
            ->get('/admin/dashboard')
            ->assertRedirect('/');
    }

    public function test_admin_can_create_new_affiliate(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this
            ->actingAs($admin)
            ->post('/admin/affiliates', [
                'name' => 'Affiliate Test User',
                'email' => 'affiliate-test@example.com',
                'password' => 'password123',
                'referral_code' => '',
                'bank_name' => 'BCA',
                'bank_account_number' => '1234567890',
                'bank_account_name' => 'Affiliate Test User',
            ]);

        $response->assertRedirect(route('admin.affiliates.index'));

        $this->assertDatabaseHas('users', [
            'email' => 'affiliate-test@example.com',
            'role' => 'affiliate',
        ]);

        $createdAffiliate = Affiliate::query()
            ->whereHas('user', function ($query): void {
                $query->where('email', 'affiliate-test@example.com');
            })
            ->first();

        $this->assertNotNull($createdAffiliate);
        $this->assertStringStartsWith('aff_', $createdAffiliate->referral_code);
    }
}

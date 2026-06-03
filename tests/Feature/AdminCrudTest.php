<?php

namespace Tests\Feature;

use App\Models\Affiliate;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_vendor_and_affiliate(): void
    {
        $admin = User::factory()->admin()->create();
        $vendor = Vendor::factory()->create([
            'company_name' => 'Legacy Vendor Co',
            'commission_type' => 'flat',
            'commission_value' => 15000,
            'cookie_duration_days' => 30,
        ]);
        $affiliate = Affiliate::factory()->create([
            'referral_code' => 'aff_legacy01',
            'bank_name' => 'BCA',
            'bank_account_number' => '1111111111',
            'bank_account_name' => 'Legacy Affiliate',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.vendors.update', $vendor), [
                'editing_vendor_id' => $vendor->id,
                'name' => 'Updated Vendor Owner',
                'email' => 'updated-vendor@example.com',
                'company_name' => 'Updated Vendor Company',
                'commission_type' => 'percentage',
                'commission_value' => 12.5,
                'cookie_duration_days' => 45,
            ])
            ->assertRedirect(route('admin.vendors.index'));

        $this->actingAs($admin)
            ->put(route('admin.affiliates.update', $affiliate), [
                'editing_affiliate_id' => $affiliate->id,
                'name' => 'Updated Affiliate User',
                'email' => 'updated-affiliate@example.com',
                'referral_code' => 'aff_updated01',
                'bank_name' => 'Mandiri',
                'bank_account_number' => '2222222222',
                'bank_account_name' => 'Updated Affiliate User',
            ])
            ->assertRedirect(route('admin.affiliates.index'));

        $this->assertDatabaseHas('vendors', [
            'id' => $vendor->id,
            'company_name' => 'Updated Vendor Company',
            'commission_type' => 'percentage',
            'commission_value' => '12.50',
            'cookie_duration_days' => 45,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $vendor->user_id,
            'name' => 'Updated Vendor Owner',
            'email' => 'updated-vendor@example.com',
            'role' => 'vendor',
        ]);

        $this->assertDatabaseHas('affiliates', [
            'id' => $affiliate->id,
            'referral_code' => 'aff_updated01',
            'bank_name' => 'Mandiri',
            'bank_account_number' => '2222222222',
            'bank_account_name' => 'Updated Affiliate User',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $affiliate->user_id,
            'name' => 'Updated Affiliate User',
            'email' => 'updated-affiliate@example.com',
            'role' => 'affiliate',
        ]);
    }

    public function test_admin_can_delete_vendor_and_affiliate(): void
    {
        $admin = User::factory()->admin()->create();
        $vendor = Vendor::factory()->create();
        $affiliate = Affiliate::factory()->create();

        $vendorUserId = $vendor->user_id;
        $affiliateUserId = $affiliate->user_id;

        $this->actingAs($admin)
            ->delete(route('admin.vendors.destroy', $vendor))
            ->assertRedirect(route('admin.vendors.index'));

        $this->actingAs($admin)
            ->delete(route('admin.affiliates.destroy', $affiliate))
            ->assertRedirect(route('admin.affiliates.index'));

        $this->assertDatabaseMissing('vendors', [
            'id' => $vendor->id,
        ]);

        $this->assertDatabaseMissing('users', [
            'id' => $vendorUserId,
        ]);

        $this->assertDatabaseMissing('affiliates', [
            'id' => $affiliate->id,
        ]);

        $this->assertDatabaseMissing('users', [
            'id' => $affiliateUserId,
        ]);
    }
}

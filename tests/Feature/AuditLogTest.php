<?php

namespace Tests\Feature;

use App\Models\Affiliate;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_actions_create_audit_logs(): void
    {
        $admin = User::factory()->admin()->create();

        // 1. Test Vendor Create Log
        $this->actingAs($admin)
            ->post(route('admin.vendors.store'), [
                'name' => 'New Vendor',
                'email' => 'new-vendor@example.com',
                'password' => 'password123',
                'company_name' => 'New Vendor LLC',
                'website_url' => 'https://new-vendor.com',
                'commission_type' => 'flat',
                'commission_value' => 10000,
            ])
            ->assertRedirect(route('admin.vendors.index'));

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'admin.vendor.create',
        ]);

        $vendor = Vendor::where('company_name', 'New Vendor LLC')->first();
        $this->assertNotNull($vendor);

        // 2. Test Vendor Update Log
        $this->actingAs($admin)
            ->put(route('admin.vendors.update', $vendor), [
                'editing_vendor_id' => $vendor->id,
                'name' => 'Updated Vendor Name',
                'email' => 'updated-vendor@example.com',
                'company_name' => 'Updated Vendor LLC',
                'website_url' => 'https://updated-vendor.com',
                'commission_type' => 'percentage',
                'commission_value' => 10,
            ])
            ->assertRedirect(route('admin.vendors.index'));

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'admin.vendor.update',
        ]);

        // 3. Test Vendor Delete Log
        $this->actingAs($admin)
            ->delete(route('admin.vendors.destroy', $vendor))
            ->assertRedirect(route('admin.vendors.index'));

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'admin.vendor.delete',
        ]);
    }

    public function test_admin_affiliate_actions_create_audit_logs(): void
    {
        $admin = User::factory()->admin()->create();

        // 1. Test Affiliate Create Log
        $this->actingAs($admin)
            ->post(route('admin.affiliates.store'), [
                'name' => 'New Affiliate',
                'email' => 'new-affiliate@example.com',
                'password' => 'password123',
                'referral_code' => 'aff_new_test',
                'bank_name' => 'BCA',
                'bank_account_number' => '999999',
                'bank_account_name' => 'New Affiliate User',
            ])
            ->assertRedirect(route('admin.affiliates.index'));

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'admin.affiliate.create',
        ]);

        $affiliate = Affiliate::where('referral_code', 'aff_new_test')->first();
        $this->assertNotNull($affiliate);

        // 2. Test Affiliate Update Log
        $this->actingAs($admin)
            ->put(route('admin.affiliates.update', $affiliate), [
                'editing_affiliate_id' => $affiliate->id,
                'name' => 'Updated Affiliate Name',
                'email' => 'updated-affiliate@example.com',
                'referral_code' => 'aff_updated_test',
                'bank_name' => 'Mandiri',
                'bank_account_number' => '888888',
                'bank_account_name' => 'Updated Affiliate User',
            ])
            ->assertRedirect(route('admin.affiliates.index'));

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'admin.affiliate.update',
        ]);

        // 3. Test Affiliate Delete Log
        $this->actingAs($admin)
            ->delete(route('admin.affiliates.destroy', $affiliate))
            ->assertRedirect(route('admin.affiliates.index'));

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'admin.affiliate.delete',
        ]);
    }

    public function test_vendor_settings_update_creates_audit_log(): void
    {
        $vendor = Vendor::factory()->create();

        $this->actingAs($vendor->user)
            ->put(route('vendor.integration.settings.update'), [
                'website_url' => 'https://my-vendor-website.com',
                'webhook_url' => 'https://my-vendor-website.com/webhook',
                'cookie_duration_days' => 45,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $vendor->user_id,
            'action' => 'vendor.settings.update',
        ]);
    }
}

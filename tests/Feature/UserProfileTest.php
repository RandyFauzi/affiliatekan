<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Affiliate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_profile(): void
    {
        $response = $this->get('/profile');
        $response->assertRedirect('/login');

        $response = $this->put('/profile', ['name' => 'New Name']);
        $response->assertRedirect('/login');
    }

    public function test_user_can_view_profile_edit_page(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($user)->get('/profile');
        $response->assertOk();
        $response->assertViewIs('profile.edit');
    }

    public function test_admin_can_update_core_profile(): void
    {
        $admin = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@admin.com',
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->put('/profile', [
            'name' => 'New Admin Name',
            'email' => 'new@admin.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect('/profile');
        $response->assertSessionHas('status');

        $admin->refresh();
        $this->assertEquals('New Admin Name', $admin->name);
        $this->assertEquals('new@admin.com', $admin->email);
        $this->assertTrue(Hash::check('newpassword123', $admin->password));
    }

    public function test_vendor_can_update_profile_and_company_details(): void
    {
        $user = User::factory()->create([
            'role' => 'vendor',
        ]);
        $vendor = Vendor::factory()->create([
            'user_id' => $user->id,
            'company_name' => 'Old Company',
            'website_url' => 'https://old.com',
        ]);

        $response = $this->actingAs($user)->put('/profile', [
            'name' => 'Vendor Owner',
            'email' => 'vendor@test.com',
            'company_name' => 'New Company Name LLC',
            'website_url' => 'https://newcompany.com',
            'commission_type' => 'percentage',
            'commission_value' => 15,
            'cookie_duration_days' => 45,
        ]);

        $response->assertRedirect('/profile');
        $user->refresh();
        $vendor->refresh();

        $this->assertEquals('Vendor Owner', $user->name);
        $this->assertEquals('vendor@test.com', $user->email);
        $this->assertEquals('New Company Name LLC', $vendor->company_name);
        $this->assertEquals('https://newcompany.com', $vendor->website_url);
        $this->assertEquals('percentage', $vendor->commission_type);
        $this->assertEquals(15, $vendor->commission_value);
        $this->assertEquals(45, $vendor->cookie_duration_days);
    }

    public function test_affiliate_can_update_profile_and_banking_details(): void
    {
        $user = User::factory()->create([
            'role' => 'affiliate',
        ]);
        $affiliate = Affiliate::factory()->create([
            'user_id' => $user->id,
            'bank_name' => 'Old Bank',
            'bank_account_number' => '111',
            'bank_account_name' => 'Old Account',
        ]);

        $response = $this->actingAs($user)->put('/profile', [
            'name' => 'Affiliate Hero',
            'email' => 'affiliate@test.com',
            'bank_name' => 'BCA',
            'bank_account_number' => '999888777',
            'bank_account_name' => 'Hero Account',
        ]);

        $response->assertRedirect('/profile');
        $user->refresh();
        $affiliate->refresh();

        $this->assertEquals('Affiliate Hero', $user->name);
        $this->assertEquals('affiliate@test.com', $user->email);
        $this->assertEquals('BCA', $affiliate->bank_name);
        $this->assertEquals('999888777', $affiliate->bank_account_number);
        $this->assertEquals('Hero Account', $affiliate->bank_account_name);
    }
}

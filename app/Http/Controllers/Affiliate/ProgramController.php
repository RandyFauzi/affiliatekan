<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProgramController extends Controller
{
    public function index(Request $request): View
    {
        $authenticatedAffiliate = $this->resolveAuthenticatedAffiliate($request);

        // Ensure dummy vendor "Onfix" exists in the database for a high-fidelity experience
        $this->ensureDummyVendorExists();

        // Get all vendors with valid website URLs
        $vendors = Vendor::query()
            ->whereNotNull('website_url')
            ->where('website_url', '!=', '')
            ->orderBy('company_name')
            ->get();

        // Get array of joined vendor IDs
        $joinedVendorIds = $authenticatedAffiliate->vendors->pluck('id')->toArray();

        return view('affiliate.programs.index', [
            'authenticatedAffiliate' => $authenticatedAffiliate,
            'vendors' => $vendors,
            'joinedVendorIds' => $joinedVendorIds,
        ]);
    }

    public function join(Request $request, Vendor $vendor): RedirectResponse
    {
        $authenticatedAffiliate = $this->resolveAuthenticatedAffiliate($request);

        // Attach vendor to the affiliate in the pivot table (agreed_to_terms defaults to true)
        if (!$authenticatedAffiliate->vendors()->where('vendor_id', $vendor->id)->exists()) {
            $authenticatedAffiliate->vendors()->attach($vendor->id, [
                'agreed_to_terms' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return back()->with('status', "Anda berhasil mendaftar dan bergabung ke program kemitraan {$vendor->company_name}!");
    }

    private function resolveAuthenticatedAffiliate(Request $request): Affiliate
    {
        $authenticatedAffiliate = $request->user()?->affiliate;
        abort_if($authenticatedAffiliate === null, 403, 'Affiliate account is required.');
        return $authenticatedAffiliate;
    }

    private function ensureDummyVendorExists(): void
    {
        $dummyEmail = 'onfix@onfix.com';
        $dummyUser = User::query()->where('email', $dummyEmail)->first();

        if (!$dummyUser) {
            $dummyUser = User::query()->create([
                'name' => 'Onfix Jasa Cuci AC',
                'email' => $dummyEmail,
                'password' => Hash::make('password123'),
                'role' => 'vendor',
            ]);
        }

        $dummyVendor = Vendor::query()->where('user_id', $dummyUser->id)->first();
        if (!$dummyVendor) {
            Vendor::query()->create([
                'user_id' => $dummyUser->id,
                'company_name' => 'Onfix - Jasa Cuci AC & Maintenance',
                'website_url' => 'https://onfix.co.id',
                'commission_type' => 'flat',
                'commission_value' => 15000.00,
                'cookie_duration_days' => 30,
            ]);
        }
    }
}

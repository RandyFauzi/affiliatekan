<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminVendorRequest;
use App\Http\Requests\UpdateAdminVendorRequest;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VendorController extends Controller
{
    public function index(): View
    {
        $vendorDirectoryEntries = Vendor::query()
            ->with('user')
            ->withCount(['conversions', 'payouts'])
            ->latest()
            ->get();

        return view('admin.vendors.index', [
            'vendorDirectoryEntries' => $vendorDirectoryEntries,
        ]);
    }

    public function store(StoreAdminVendorRequest $request): RedirectResponse
    {
        $validatedPayload = $request->validated();

        DB::transaction(function () use ($validatedPayload): void {
            $vendorUser = User::query()->create([
                'name' => $validatedPayload['name'],
                'email' => $validatedPayload['email'],
                'password' => $validatedPayload['password'],
                'role' => 'vendor',
            ]);

            Vendor::query()->create([
                'user_id' => $vendorUser->id,
                'company_name' => $validatedPayload['company_name'],
                'website_url' => $validatedPayload['website_url'] ?? null,
                'commission_type' => $validatedPayload['commission_type'],
                'commission_value' => $validatedPayload['commission_value'],
                'cookie_duration_days' => $validatedPayload['cookie_duration_days'] ?? 30,
            ]);
        });

        return redirect()
            ->route('admin.vendors.index')
            ->with('status', 'New vendor account created successfully.');
    }

    public function update(UpdateAdminVendorRequest $request, Vendor $vendor): RedirectResponse
    {
        $validatedPayload = $request->validated();

        DB::transaction(function () use ($validatedPayload, $vendor): void {
            $vendor->user()->update([
                'name' => $validatedPayload['name'],
                'email' => $validatedPayload['email'],
            ]);

            $vendor->update([
                'company_name' => $validatedPayload['company_name'],
                'website_url' => $validatedPayload['website_url'] ?? null,
                'commission_type' => $validatedPayload['commission_type'],
                'commission_value' => $validatedPayload['commission_value'],
                'cookie_duration_days' => $validatedPayload['cookie_duration_days'] ?? 30,
            ]);
        });

        return redirect()
            ->route('admin.vendors.index')
            ->with('status', 'Vendor updated successfully.');
    }

    public function destroy(Vendor $vendor): RedirectResponse
    {
        DB::transaction(function () use ($vendor): void {
            $managedVendorUser = $vendor->user;

            if ($managedVendorUser instanceof User) {
                $managedVendorUser->delete();

                return;
            }

            $vendor->delete();
        });

        return redirect()
            ->route('admin.vendors.index')
            ->with('status', 'Vendor deleted successfully.');
    }
}

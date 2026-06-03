<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminAffiliateRequest;
use App\Http\Requests\UpdateAdminAffiliateRequest;
use App\Models\Affiliate;
use App\Models\User;
use App\Services\TrackingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AffiliateController extends Controller
{
    public function __construct(
        private readonly TrackingService $trackingService
    ) {
    }

    public function index(): View
    {
        $affiliateDirectoryEntries = Affiliate::query()
            ->with('user')
            ->withCount(['clickLogs', 'conversions'])
            ->latest()
            ->get();

        return view('admin.affiliates.index', [
            'affiliateDirectoryEntries' => $affiliateDirectoryEntries,
        ]);
    }

    public function store(StoreAdminAffiliateRequest $request): RedirectResponse
    {
        $validatedPayload = $request->validated();

        $affiliate = DB::transaction(function () use ($validatedPayload) {
            $affiliateUser = User::query()->create([
                'name' => $validatedPayload['name'],
                'email' => $validatedPayload['email'],
                'password' => $validatedPayload['password'],
                'role' => 'affiliate',
            ]);

            return Affiliate::query()->create([
                'user_id' => $affiliateUser->id,
                'referral_code' => $validatedPayload['referral_code'] ?: $this->trackingService->generateAffiliateCode(),
                'bank_name' => $validatedPayload['bank_name'] ?: null,
                'bank_account_number' => $validatedPayload['bank_account_number'] ?: null,
                'bank_account_name' => $validatedPayload['bank_account_name'] ?: null,
            ]);
        });

        \App\Services\AuditLogger::log('admin.affiliate.create', [
            'affiliate_id' => $affiliate->id,
            'referral_code' => $affiliate->referral_code,
        ]);

        return redirect()
            ->route('admin.affiliates.index')
            ->with('status', 'New affiliate account created successfully.');
    }

    public function update(UpdateAdminAffiliateRequest $request, Affiliate $affiliate): RedirectResponse
    {
        $validatedPayload = $request->validated();

        DB::transaction(function () use ($validatedPayload, $affiliate): void {
            $affiliate->user()->update([
                'name' => $validatedPayload['name'],
                'email' => $validatedPayload['email'],
            ]);

            $affiliate->update([
                'referral_code' => $validatedPayload['referral_code'],
                'bank_name' => $validatedPayload['bank_name'] ?: null,
                'bank_account_number' => $validatedPayload['bank_account_number'] ?: null,
                'bank_account_name' => $validatedPayload['bank_account_name'] ?: null,
            ]);
        });

        \App\Services\AuditLogger::log('admin.affiliate.update', [
            'affiliate_id' => $affiliate->id,
            'referral_code' => $affiliate->referral_code,
        ]);

        return redirect()
            ->route('admin.affiliates.index')
            ->with('status', 'Affiliate updated successfully.');
    }

    public function destroy(Affiliate $affiliate): RedirectResponse
    {
        $affiliateId = $affiliate->id;
        $referralCode = $affiliate->referral_code;

        DB::transaction(function () use ($affiliate): void {
            $managedAffiliateUser = $affiliate->user;

            if ($managedAffiliateUser instanceof User) {
                $managedAffiliateUser->delete();

                return;
            }

            $affiliate->delete();
        });

        \App\Services\AuditLogger::log('admin.affiliate.delete', [
            'affiliate_id' => $affiliateId,
            'referral_code' => $referralCode,
        ]);

        return redirect()
            ->route('admin.affiliates.index')
            ->with('status', 'Affiliate deleted successfully.');
    }
}

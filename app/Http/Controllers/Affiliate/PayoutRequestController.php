<?php

namespace App\Http\Controllers\Affiliate;

use App\Mail\PayoutRequestedForAffiliate;
use App\Mail\PayoutRequestedForVendor;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAffiliateBankAccountDetailsRequest;
use App\Models\Affiliate;
use App\Models\Conversion;
use App\Models\Payout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class PayoutRequestController extends Controller
{
    public function index(Request $request): View
    {
        $authenticatedAffiliate = $this->resolveAuthenticatedAffiliate($request);

        $availableBalance = (float) $authenticatedAffiliate->conversions()
            ->where('status', 'approved')
            ->whereNull('payout_id')
            ->sum('commission_amount');

        $pendingBalance = (float) $authenticatedAffiliate->conversions()
            ->where('status', 'pending')
            ->whereNull('payout_id')
            ->sum('commission_amount');

        $processingBalance = (float) $authenticatedAffiliate->payouts()
            ->whereIn('status', ['requested', 'processing'])
            ->sum('amount');

        $paidBalance = (float) $authenticatedAffiliate->payouts()
            ->where('status', 'paid')
            ->sum('amount');

        $payouts = $authenticatedAffiliate->payouts()
            ->with('vendor')
            ->latest()
            ->get();

        $eligiblePayoutTotalAmount = (float) $authenticatedAffiliate->conversions()
            ->whereNull('payout_id')
            ->whereIn('status', ['approved', 'pending'])
            ->sum('commission_amount');

        return view('affiliate.payouts.index', [
            'authenticatedAffiliate' => $authenticatedAffiliate,
            'payouts' => $payouts,
            'payoutSummary' => [
                'available_balance' => $availableBalance,
                'pending_balance' => $pendingBalance,
                'processing_balance' => $processingBalance,
                'paid_balance' => $paidBalance,
                'eligible_payout_total_amount' => $eligiblePayoutTotalAmount,
            ],
        ]);
    }

    public function updateBankAccount(UpdateAffiliateBankAccountDetailsRequest $request): RedirectResponse
    {
        $authenticatedAffiliate = $this->resolveAuthenticatedAffiliate($request);
        $validatedPayload = $request->validated();

        $authenticatedAffiliate->update($validatedPayload);

        // Log the audit event
        \App\Services\AuditLogger::log('bank_details.update', [
            'bank_name' => $validatedPayload['bank_name'] ?? null,
            'bank_account_number' => $validatedPayload['bank_account_number'] ?? null,
            'bank_account_name' => $validatedPayload['bank_account_name'] ?? null,
        ]);

        return back()->with('status', 'Informasi rekening bank berhasil disimpan.');
    }

    public function store(Request $request): RedirectResponse
    {
        $authenticatedAffiliate = $this->resolveAuthenticatedAffiliate($request);

        if (! $this->hasCompleteBankAccountDetails($authenticatedAffiliate)) {
            return back()->withErrors([
                'payout_request' => 'Lengkapi informasi rekening bank sebelum mengajukan payout.',
            ]);
        }

        $eligibleConversions = Conversion::query()
            ->where('affiliate_id', $authenticatedAffiliate->id)
            ->whereNull('payout_id')
            ->whereIn('status', ['pending', 'approved'])
            ->get(['id', 'vendor_id', 'commission_amount']);

        if ($eligibleConversions->isEmpty()) {
            return back()->withErrors([
                'payout_request' => 'Belum ada komisi yang bisa diajukan untuk payout.',
            ]);
        }

        $createdPayoutIds = [];

        DB::transaction(function () use ($authenticatedAffiliate, $eligibleConversions, &$createdPayoutIds): void {
            $eligibleConversions
                ->groupBy('vendor_id')
                ->each(function ($vendorConversions, $vendorId) use ($authenticatedAffiliate, &$createdPayoutIds): void {
                    $createdPayout = Payout::query()->create([
                        'affiliate_id' => $authenticatedAffiliate->id,
                        'vendor_id' => (int) $vendorId,
                        'amount' => (float) $vendorConversions->sum('commission_amount'),
                        'status' => 'requested',
                    ]);

                    Conversion::query()
                        ->whereIn('id', $vendorConversions->pluck('id'))
                        ->update([
                            'payout_id' => $createdPayout->id,
                        ]);

                    $createdPayoutIds[] = $createdPayout->id;
                });
        });

        Payout::query()
            ->with(['affiliate.user', 'vendor.user'])
            ->whereIn('id', $createdPayoutIds)
            ->get()
            ->each(fn (Payout $createdPayout) => $this->dispatchRequestedPayoutNotifications($createdPayout));

        // Log the audit event
        \App\Services\AuditLogger::log('payout.request', [
            'payout_ids' => $createdPayoutIds,
            'total_amount' => (float)$eligibleConversions->sum('commission_amount'),
        ]);

        return back()->with('status', 'Permintaan payout berhasil dikirim ke vendor terkait.');
    }

    private function resolveAuthenticatedAffiliate(Request $request): Affiliate
    {
        $authenticatedAffiliate = $request->user()?->affiliate;

        abort_if($authenticatedAffiliate === null, 403, 'Affiliate account is required to access this action.');

        return $authenticatedAffiliate;
    }

    private function hasCompleteBankAccountDetails(Affiliate $affiliate): bool
    {
        return filled($affiliate->bank_name)
            && filled($affiliate->bank_account_number)
            && filled($affiliate->bank_account_name);
    }

    private function dispatchRequestedPayoutNotifications(Payout $createdPayout): void
    {
        try {
            $vendorEmailAddress = $createdPayout->vendor?->user?->email;
            $affiliateEmailAddress = $createdPayout->affiliate?->user?->email;

            if (filled($vendorEmailAddress)) {
                Mail::to($vendorEmailAddress)->queue(new PayoutRequestedForVendor($createdPayout));
            }

            if (filled($affiliateEmailAddress)) {
                Mail::to($affiliateEmailAddress)->queue(new PayoutRequestedForAffiliate($createdPayout));
            }
        } catch (Throwable $throwable) {
            Log::warning('Affiliate payout request email dispatch failed.', [
                'payout_id' => $createdPayout->id,
                'message' => $throwable->getMessage(),
            ]);
        }
    }
}

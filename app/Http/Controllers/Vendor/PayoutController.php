<?php

namespace App\Http\Controllers\Vendor;

use App\Mail\PayoutCompletedForAffiliate;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreManualPayoutRequest;
use App\Models\Payout;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

class PayoutController extends Controller
{
    public function index(Request $request): View
    {
        $authenticatedVendor = $this->resolveAuthenticatedVendor($request);

        $payoutQueueEntries = Payout::query()
            ->with(['affiliate.user'])
            ->where('vendor_id', $authenticatedVendor->id)
            ->orderByRaw("CASE WHEN status = 'requested' THEN 0 ELSE 1 END")
            ->latest()
            ->get();

        return view('vendor.payouts.index', [
            'payoutQueueEntries' => $payoutQueueEntries,
            'authenticatedVendor' => $authenticatedVendor,
        ]);
    }

    public function payManual(StoreManualPayoutRequest $request, int $payoutId): RedirectResponse
    {
        $validatedPayload = $request->validated();

        $authenticatedVendor = $this->resolveAuthenticatedVendor($request);

        $payout = Payout::query()
            ->where('vendor_id', $authenticatedVendor->id)
            ->findOrFail($payoutId);

        $storedProofOfTransferPath = $validatedPayload['proof_of_transfer_path']->store(
            'payout-proofs',
            'public'
        );

        $payout->update([
            'proof_of_transfer_path' => $storedProofOfTransferPath,
            'status' => 'paid',
        ]);

        $payout->conversions()->update([
            'status' => 'paid',
        ]);

        // Log the audit event
        \App\Services\AuditLogger::log('payout.complete', [
            'payout_id' => $payout->id,
            'amount' => (float)$payout->amount,
            'proof_path' => $storedProofOfTransferPath,
        ]);

        $payout->loadMissing(['affiliate.user', 'vendor.user']);
        $this->dispatchCompletedPayoutNotification($payout);
        $this->dispatchWebhookIfConfigured($payout, 'payout.paid');

        return redirect()->back()->with('status', 'Payout marked as paid successfully.');
    }

    public function markAsProcessing(Request $request, int $payoutId): RedirectResponse
    {
        $authenticatedVendor = $this->resolveAuthenticatedVendor($request);

        $payout = Payout::query()
            ->where('vendor_id', $authenticatedVendor->id)
            ->where('status', 'requested')
            ->findOrFail($payoutId);

        $payout->update([
            'status' => 'processing',
        ]);

        // Log the audit event
        \App\Services\AuditLogger::log('payout.processing', [
            'payout_id' => $payout->id,
            'amount' => (float)$payout->amount,
        ]);

        $this->dispatchWebhookIfConfigured($payout, 'payout.processing');

        return redirect()->back()->with('status', 'Payout marked as processing successfully.');
    }

    private function resolveAuthenticatedVendor(Request $request): Vendor
    {
        $authenticatedVendor = $request->user()?->vendor;

        abort_if($authenticatedVendor === null, 403, 'Vendor account is required to access this page.');

        return $authenticatedVendor;
    }

    private function dispatchCompletedPayoutNotification(Payout $paidPayout): void
    {
        try {
            $affiliateEmailAddress = $paidPayout->affiliate?->user?->email;

            if (blank($affiliateEmailAddress) || blank($paidPayout->proof_of_transfer_path)) {
                return;
            }

            $proofOfTransferUrl = Storage::disk('public')->url($paidPayout->proof_of_transfer_path);

            Mail::to($affiliateEmailAddress)->queue(
                new PayoutCompletedForAffiliate($paidPayout, $proofOfTransferUrl)
            );
        } catch (Throwable $throwable) {
            Log::warning('Affiliate payout completion email dispatch failed.', [
                'payout_id' => $paidPayout->id,
                'message' => $throwable->getMessage(),
            ]);
        }
    }

    private function dispatchWebhookIfConfigured(Payout $payout, string $event): void
    {
        $payout->loadMissing(['vendor', 'affiliate.user']);
        
        if ($payout->vendor && filled($payout->vendor->webhook_url)) {
            \App\Jobs\SendVendorWebhookJob::dispatch($payout->vendor, $event, [
                'id' => $payout->id,
                'affiliate_id' => $payout->affiliate_id,
                'affiliate_name' => $payout->affiliate?->user?->name,
                'affiliate_email' => $payout->affiliate?->user?->email,
                'amount' => (float) $payout->amount,
                'status' => $payout->status,
                'proof_of_transfer_path' => $payout->proof_of_transfer_path,
                'notes' => $payout->notes,
                'created_at' => $payout->created_at?->toIso8601String(),
                'updated_at' => $payout->updated_at?->toIso8601String(),
            ]);
        }
    }
}

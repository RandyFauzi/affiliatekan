<?php

namespace Tests\Feature;

use App\Mail\PayoutCompletedForAffiliate;
use App\Mail\PayoutRequestedForAffiliate;
use App\Mail\PayoutRequestedForVendor;
use App\Models\Affiliate;
use App\Models\Conversion;
use App\Models\Payout;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PayoutFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_affiliate_can_request_payout_and_requested_notifications_are_queued(): void
    {
        Mail::fake();

        $vendor = Vendor::factory()->create();
        $affiliate = Affiliate::factory()->create([
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Jago Affiliate',
        ]);

        $approvedConversion = Conversion::query()->create([
            'vendor_id' => $vendor->id,
            'affiliate_id' => $affiliate->id,
            'vendor_order_id' => 'ORDER-PAYOUT-001',
            'sale_amount' => 250000,
            'commission_amount' => 25000,
            'status' => 'approved',
        ]);

        $response = $this
            ->actingAs($affiliate->user)
            ->from(route('affiliate.dashboard.index'))
            ->post(route('affiliate.payout-requests.store'));

        $response->assertRedirect(route('affiliate.dashboard.index'));

        $createdPayout = Payout::query()->first();

        $this->assertNotNull($createdPayout);
        $this->assertDatabaseHas('payouts', [
            'id' => $createdPayout->id,
            'affiliate_id' => $affiliate->id,
            'vendor_id' => $vendor->id,
            'amount' => '25000.00',
            'status' => 'requested',
        ]);

        $approvedConversion->refresh();

        $this->assertSame($createdPayout->id, $approvedConversion->payout_id);

        Mail::assertQueued(PayoutRequestedForVendor::class, function (PayoutRequestedForVendor $mailable) use ($createdPayout, $vendor): bool {
            return $mailable->payout->is($createdPayout)
                && $vendor->user->email === $createdPayout->vendor?->user?->email;
        });

        Mail::assertQueued(PayoutRequestedForAffiliate::class, function (PayoutRequestedForAffiliate $mailable) use ($createdPayout, $affiliate): bool {
            return $mailable->payout->is($createdPayout)
                && $affiliate->user->email === $createdPayout->affiliate?->user?->email;
        });
    }

    public function test_vendor_can_mark_requested_payout_as_paid_and_completion_notification_is_queued(): void
    {
        Mail::fake();
        Storage::fake('public');

        $vendor = Vendor::factory()->create();
        $affiliate = Affiliate::factory()->create([
            'bank_name' => 'Mandiri',
            'bank_account_number' => '9876543210',
            'bank_account_name' => 'Affiliate Hebat',
        ]);

        $requestedPayout = Payout::query()->create([
            'affiliate_id' => $affiliate->id,
            'vendor_id' => $vendor->id,
            'amount' => 40000,
            'status' => 'requested',
        ]);

        $linkedConversion = Conversion::query()->create([
            'vendor_id' => $vendor->id,
            'affiliate_id' => $affiliate->id,
            'payout_id' => $requestedPayout->id,
            'vendor_order_id' => 'ORDER-PAYOUT-002',
            'sale_amount' => 300000,
            'commission_amount' => 40000,
            'status' => 'approved',
        ]);

        $response = $this
            ->actingAs($vendor->user)
            ->from(route('vendor.payouts.index'))
            ->post(route('vendor.payouts.pay-manual', $requestedPayout->id), [
                'proof_of_transfer_path' => UploadedFile::fake()->image('proof-transfer.png'),
            ]);

        $response->assertRedirect(route('vendor.payouts.index'));

        $requestedPayout->refresh();
        $linkedConversion->refresh();

        $this->assertSame('paid', $requestedPayout->status);
        $this->assertNotNull($requestedPayout->proof_of_transfer_path);
        $this->assertSame('paid', $linkedConversion->status);

        Storage::disk('public')->assertExists($requestedPayout->proof_of_transfer_path);

        Mail::assertQueued(PayoutCompletedForAffiliate::class, function (PayoutCompletedForAffiliate $mailable) use ($requestedPayout, $affiliate): bool {
            return $mailable->payout->is($requestedPayout)
                && $affiliate->user->email === $requestedPayout->affiliate?->user?->email
                && str_contains($mailable->proofUrl, $requestedPayout->proof_of_transfer_path);
        });
    }

    public function test_vendor_can_mark_requested_payout_as_processing(): void
    {
        $vendor = Vendor::factory()->create();
        $affiliate = Affiliate::factory()->create();

        $requestedPayout = Payout::query()->create([
            'affiliate_id' => $affiliate->id,
            'vendor_id' => $vendor->id,
            'amount' => 50000,
            'status' => 'requested',
        ]);

        $response = $this
            ->actingAs($vendor->user)
            ->from(route('vendor.payouts.index'))
            ->post(route('vendor.payouts.process', $requestedPayout->id));

        $response->assertRedirect(route('vendor.payouts.index'));

        $requestedPayout->refresh();
        $this->assertSame('processing', $requestedPayout->status);
    }
}

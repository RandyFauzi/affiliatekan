<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\Conversion;
use App\Models\Vendor;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;

class CommissionCalculator
{
    public function processConversion(
        int $vendorId,
        string $affiliateCode,
        string $vendorOrderId,
        int|float|string $saleAmount,
        int|float|string|null $customCommissionAmount = null,
        ?string $productName = null
    ): Conversion {
        $normalizedVendorOrderId = trim($vendorOrderId);

        if ($normalizedVendorOrderId === '') {
            throw new InvalidArgumentException('Vendor order ID must not be empty.');
        }

        if (! is_numeric($saleAmount) || (float) $saleAmount < 0) {
            throw new InvalidArgumentException('Sale amount must be a valid non-negative number.');
        }

        $conversion = DB::transaction(function () use (
            $vendorId,
            $affiliateCode,
            $normalizedVendorOrderId,
            $saleAmount,
            $customCommissionAmount,
            $productName
        ): Conversion {
            $affiliate = Affiliate::query()
                ->where('referral_code', $affiliateCode)
                ->firstOrFail();

            $vendor = Vendor::query()
                ->lockForUpdate()
                ->findOrFail($vendorId);

            $normalizedSaleAmount = round((float) $saleAmount, 2);
            
            $calculatedCommissionAmount = $customCommissionAmount !== null
                ? round((float) $customCommissionAmount, 2)
                : $this->calculateCommissionAmount(
                    $vendor->commission_type,
                    (float) $vendor->commission_value,
                    $normalizedSaleAmount
                );

            return Conversion::query()->updateOrCreate(
                [
                    'vendor_id' => $vendor->id,
                    'vendor_order_id' => $normalizedVendorOrderId,
                ],
                [
                    'affiliate_id' => $affiliate->id,
                    'product_name' => $productName,
                    'sale_amount' => $normalizedSaleAmount,
                    'commission_amount' => $calculatedCommissionAmount,
                    'status' => 'pending',
                ]
            );
        });

        $conversion->loadMissing('vendor');

        if (filled($conversion->vendor->webhook_url)) {
            \App\Jobs\SendVendorWebhookJob::dispatch($conversion->vendor, 'conversion.created', [
                'id' => $conversion->id,
                'vendor_order_id' => $conversion->vendor_order_id,
                'sale_amount' => (float) $conversion->sale_amount,
                'commission_amount' => (float) $conversion->commission_amount,
                'status' => $conversion->status,
                'affiliate_code' => $affiliateCode,
            ]);
        }

        return $conversion;
    }

    private function calculateCommissionAmount(
        string $commissionType,
        float $commissionValue,
        float $saleAmount
    ): float {
        if ($commissionType === 'flat') {
            return round($commissionValue, 2);
        }

        if ($commissionType === 'percentage') {
            return round(($saleAmount * $commissionValue) / 100, 2);
        }

        throw new InvalidArgumentException("Unsupported commission type [{$commissionType}].");
    }
}

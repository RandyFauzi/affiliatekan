<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\ClickLog;
use Carbon\CarbonInterface;
use Illuminate\Support\Str;

class TrackingService
{
    public function generateAffiliateCode(): string
    {
        do {
            $generatedAffiliateCode = 'aff_' . Str::lower(Str::random(8));
        } while (Affiliate::query()->where('referral_code', $generatedAffiliateCode)->exists());

        return $generatedAffiliateCode;
    }

    public function recordClick(
        int $affiliateId,
        int $vendorId,
        ?string $ipAddress,
        ?string $userAgent,
        ?string $refererUrl = null
    ): ClickLog|false {
        $spamPreventionWindowStart = now()->subMinutes(5);

        if ($this->hasRecentDuplicateClick($affiliateId, $vendorId, $ipAddress, $spamPreventionWindowStart)) {
            return false;
        }

        return ClickLog::query()->create([
            'affiliate_id' => $affiliateId,
            'vendor_id' => $vendorId,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'referer_url' => $refererUrl,
            'clicked_at' => now(),
        ]);
    }

    private function hasRecentDuplicateClick(
        int $affiliateId,
        int $vendorId,
        ?string $ipAddress,
        CarbonInterface $spamPreventionWindowStart
    ): bool {
        if (blank($ipAddress)) {
            return false;
        }

        return ClickLog::query()
            ->where('affiliate_id', $affiliateId)
            ->where('vendor_id', $vendorId)
            ->where('ip_address', $ipAddress)
            ->where('clicked_at', '>=', $spamPreventionWindowStart)
            ->exists();
    }
}

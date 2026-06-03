<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\Vendor;
use App\Services\TrackingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClickController extends Controller
{
    public function __construct(
        private readonly TrackingService $trackingService
    ) {
    }

    public function store(Request $request): JsonResponse
    {
        $validatedPayload = $request->validate([
            'affiliate_code' => ['nullable', 'string'],
            'ref_code' => ['nullable', 'string'],
        ]);

        $affiliateCode = $validatedPayload['affiliate_code'] ?? $validatedPayload['ref_code'] ?? null;

        if (blank($affiliateCode)) {
            return response()->json([
                'success' => false,
                'message' => 'Affiliate code is required.',
            ], 422);
        }

        $affiliate = Affiliate::query()
            ->where('referral_code', $affiliateCode)
            ->first();

        if ($affiliate === null) {
            return response()->json([
                'success' => false,
                'message' => 'Affiliate code was not found.',
            ], 404);
        }

        $resolvedVendor = $this->resolveVendorFromTrackingRequest($request);

        if ($resolvedVendor === null) {
            return response()->json([
                'success' => false,
                'message' => 'Vendor website could not be resolved from this tracking request.',
            ], 422);
        }

        $this->trackingService->recordClick(
            $affiliate->id,
            $resolvedVendor->id,
            $request->ip(),
            $request->userAgent(),
            $request->headers->get('Referer')
        );

        return response()->json([
            'success' => true,
            'cookie_duration_days' => (int) ($resolvedVendor->cookie_duration_days ?? 30),
        ]);
    }

    private function resolveVendorFromTrackingRequest(Request $request): ?Vendor
    {
        $trackingSourceUrl = $request->headers->get('Origin')
            ?? $request->headers->get('Referer');

        $normalizedTrackingHost = $this->normalizeTrackingHost($trackingSourceUrl);

        if ($normalizedTrackingHost === null) {
            return null;
        }

        return Vendor::query()
            ->whereNotNull('website_url')
            ->get()
            ->first(fn (Vendor $vendor) => $this->normalizeTrackingHost($vendor->website_url) === $normalizedTrackingHost);
    }

    private function normalizeTrackingHost(?string $trackingSourceUrl): ?string
    {
        if (blank($trackingSourceUrl)) {
            return null;
        }

        $parsedHost = parse_url($trackingSourceUrl, PHP_URL_HOST);

        if (! is_string($parsedHost) || $parsedHost === '') {
            $parsedHost = parse_url('https://' . ltrim($trackingSourceUrl, '/'), PHP_URL_HOST);
        }

        if (! is_string($parsedHost) || $parsedHost === '') {
            return null;
        }

        return strtolower(preg_replace('/^www\./', '', $parsedHost));
    }
}

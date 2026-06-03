<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\ClickLog;
use App\Models\Conversion;
use App\Models\Payout;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $vendorId = $request->attributes->get('vendor_id');

        $totalClicks = ClickLog::query()
            ->where('vendor_id', $vendorId)
            ->count();

        $totalConversions = Conversion::query()
            ->where('vendor_id', $vendorId)
            ->whereIn('status', ['approved', 'paid'])
            ->count();

        $totalCommissions = (float) Conversion::query()
            ->where('vendor_id', $vendorId)
            ->whereIn('status', ['approved', 'paid'])
            ->sum('commission_amount');

        $pendingPayouts = (float) Payout::query()
            ->where('vendor_id', $vendorId)
            ->whereIn('status', ['requested', 'processing'])
            ->sum('amount');

        return response()->json([
            'total_clicks' => $totalClicks,
            'total_conversions' => $totalConversions,
            'total_commissions' => $totalCommissions,
            'pending_payouts' => $pendingPayouts,
        ]);
    }
}

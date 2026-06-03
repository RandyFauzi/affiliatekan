<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\ClickLog;
use App\Models\Conversion;
use App\Models\Payout;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $authenticatedVendor = $this->resolveAuthenticatedVendor($request);
        $vendorId = $authenticatedVendor->id;

        $conversionStatusesCountedAsCompleted = ['approved', 'paid'];
        $conversionStatusesCountedAsDebt = ['pending', 'approved'];

        $totalClicks = ClickLog::query()
            ->where('vendor_id', $vendorId)
            ->count();

        $totalConversions = Conversion::query()
            ->where('vendor_id', $vendorId)
            ->whereIn('status', $conversionStatusesCountedAsCompleted)
            ->count();

        $conversionDebtAmount = (float) Conversion::query()
            ->where('vendor_id', $vendorId)
            ->whereIn('status', $conversionStatusesCountedAsDebt)
            ->sum('commission_amount');

        $requestedPayoutAmount = (float) Payout::query()
            ->where('vendor_id', $vendorId)
            ->where('status', 'requested')
            ->sum('amount');

        $totalPaid = (float) Payout::query()
            ->where('vendor_id', $vendorId)
            ->where('status', 'paid')
            ->sum('amount');

        $topAffiliates = Affiliate::query()
            ->with('user')
            ->withCount([
                'clickLogs as vendor_clicks_count' => fn ($query) => $query->where('vendor_id', $vendorId),
                'conversions as vendor_conversions_count' => fn ($query) => $query
                    ->where('vendor_id', $vendorId)
                    ->whereIn('status', $conversionStatusesCountedAsCompleted),
            ])
            ->withSum([
                'conversions as vendor_total_commission' => fn ($query) => $query
                    ->where('vendor_id', $vendorId)
                    ->whereIn('status', $conversionStatusesCountedAsCompleted),
            ], 'commission_amount')
            ->where(function ($query) use ($vendorId): void {
                $query->whereHas('clickLogs', fn ($clickQuery) => $clickQuery->where('vendor_id', $vendorId))
                    ->orWhereHas('conversions', fn ($conversionQuery) => $conversionQuery->where('vendor_id', $vendorId));
            })
            ->orderByDesc('vendor_clicks_count')
            ->orderByDesc('vendor_clicks_count')
            ->limit(5)
            ->get();

        // Load 5 most recent conversions for the cockpit table
        $recentConversions = Conversion::query()
            ->where('vendor_id', $vendorId)
            ->with('affiliate.user')
            ->latest()
            ->limit(5)
            ->get();

        return view('vendor.dashboard.index', [
            'authenticatedVendor' => $authenticatedVendor,
            'vendorDashboardMetrics' => [
                'total_clicks' => $totalClicks,
                'total_conversions' => $totalConversions,
                'total_debt' => $requestedPayoutAmount > 0 ? $requestedPayoutAmount : $conversionDebtAmount,
                'total_paid' => $totalPaid,
            ],
            'topAffiliates' => $topAffiliates,
            'recentConversions' => $recentConversions,
        ]);
    }

    private function resolveAuthenticatedVendor(Request $request): Vendor
    {
        $authenticatedVendor = $request->user()?->vendor;

        abort_if($authenticatedVendor === null, 403, 'Vendor account is required to access this page.');

        return $authenticatedVendor;
    }
}

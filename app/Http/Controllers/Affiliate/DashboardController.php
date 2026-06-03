<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $authenticatedAffiliate = $this->resolveAuthenticatedAffiliate($request);

        $approvedAndPendingStatuses = ['approved', 'pending'];
        $eligiblePayoutConversionQuery = $authenticatedAffiliate->conversions()
            ->whereNull('payout_id')
            ->whereIn('status', $approvedAndPendingStatuses);

        $totalClicks = $authenticatedAffiliate->clickLogs()->count();
        $totalConversions = $authenticatedAffiliate->conversions()
            ->whereIn('status', $approvedAndPendingStatuses)
            ->count();
        $availableBalance = (float) $authenticatedAffiliate->conversions()
            ->where('status', 'approved')
            ->whereNull('payout_id')
            ->sum('commission_amount');
        $pendingBalance = (float) $authenticatedAffiliate->conversions()
            ->where('status', 'pending')
            ->whereNull('payout_id')
            ->sum('commission_amount');
        $requestedPayoutAmount = (float) $authenticatedAffiliate->payouts()
            ->where('status', 'requested')
            ->sum('amount');
        $vendors = Vendor::query()
            ->whereNotNull('website_url')
            ->where('website_url', '!=', '')
            ->orderBy('company_name')
            ->get();
        $eligiblePayoutTotalAmount = (float) (clone $eligiblePayoutConversionQuery)->sum('commission_amount');
        $eligiblePayoutVendorCount = (clone $eligiblePayoutConversionQuery)
            ->select('vendor_id')
            ->distinct()
            ->count('vendor_id');

        $todaysEarnings = (float) $authenticatedAffiliate->conversions()
            ->whereIn('status', ['pending', 'approved', 'paid'])
            ->whereDate('created_at', today())
            ->sum('commission_amount');

        $thisMonthsEarnings = (float) $authenticatedAffiliate->conversions()
            ->whereIn('status', ['pending', 'approved', 'paid'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('commission_amount');

        $now = now();
        $daysInMonth = $now->daysInMonth;
        $startOffset = $now->startOfMonth()->dayOfWeekIso - 1; // 0 for Monday (Senin), 6 for Sunday (Minggu)
        $currentMonthYear = $now->translatedFormat('F Y');

        $currentMonthConversions = $authenticatedAffiliate->conversions()
            ->whereIn('status', ['pending', 'approved', 'paid'])
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->get();

        $dailyEarnings = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dailyEarnings[$d] = 0;
        }

        foreach ($currentMonthConversions as $conversion) {
            $day = $conversion->created_at->day;
            if (isset($dailyEarnings[$day])) {
                $dailyEarnings[$day] += (float) $conversion->commission_amount;
            }
        }

        // Load 5 most recent conversions for the cockpit table
        $recentConversions = $authenticatedAffiliate->conversions()
            ->with('vendor')
            ->latest()
            ->limit(5)
            ->get();

        return view('affiliate.dashboard.index', [
            'authenticatedAffiliate' => $authenticatedAffiliate,
            'affiliateAnalyticsSummary' => [
                'total_clicks' => $totalClicks,
                'total_conversions' => $totalConversions,
                'available_balance' => $availableBalance,
                'pending_balance' => $pendingBalance,
                'requested_payout_amount' => $requestedPayoutAmount,
                'eligible_payout_total_amount' => $eligiblePayoutTotalAmount,
                'eligible_payout_vendor_count' => $eligiblePayoutVendorCount,
                'todays_earnings' => $todaysEarnings,
                'this_months_earnings' => $thisMonthsEarnings,
            ],
            'vendors' => $vendors,
            'recentConversions' => $recentConversions,
            'dailyEarnings' => $dailyEarnings,
            'daysInMonth' => $daysInMonth,
            'startOffset' => $startOffset,
            'currentMonthYear' => $currentMonthYear,
        ]);
    }

    private function resolveAuthenticatedAffiliate(Request $request): Affiliate
    {
        $authenticatedAffiliate = $request->user()?->affiliate;

        abort_if($authenticatedAffiliate === null, 403, 'Affiliate account is required to access this page.');

        return $authenticatedAffiliate;
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\Conversion;
use App\Models\Vendor;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $grossCommissionStatuses = ['approved', 'paid'];

        return view('admin.dashboard', [
            'adminDashboardMetrics' => [
                'active_vendor_total' => Vendor::query()->count(),
                'active_affiliate_total' => Affiliate::query()->count(),
                'conversion_total' => Conversion::query()->count(),
                'gross_commission_total' => (float) Conversion::query()
                    ->whereIn('status', $grossCommissionStatuses)
                    ->sum('commission_amount'),
            ],
        ]);
    }
}

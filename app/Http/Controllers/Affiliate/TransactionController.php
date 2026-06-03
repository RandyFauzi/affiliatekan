<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $authenticatedAffiliate = $this->resolveAuthenticatedAffiliate($request);

        $transactions = $authenticatedAffiliate->conversions()
            ->with('vendor')
            ->latest()
            ->paginate(10);

        return view('affiliate.transactions.index', [
            'transactions' => $transactions,
            'authenticatedAffiliate' => $authenticatedAffiliate,
        ]);
    }

    private function resolveAuthenticatedAffiliate(Request $request): Affiliate
    {
        $authenticatedAffiliate = $request->user()?->affiliate;

        abort_if($authenticatedAffiliate === null, 403, 'Affiliate account is required to access this page.');

        return $authenticatedAffiliate;
    }
}

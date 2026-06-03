<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AffiliateListController extends Controller
{
    public function index(Request $request): View
    {
        $authenticatedVendor = $this->resolveAuthenticatedVendor($request);

        // Fetch affiliates that are registered to this vendor's program through the pivot table
        $affiliates = $authenticatedVendor->affiliates()
            ->with('user')
            ->withPivot('agreed_to_terms', 'created_at')
            ->orderByPivot('created_at', 'desc')
            ->get();

        return view('vendor.affiliates.index', [
            'authenticatedVendor' => $authenticatedVendor,
            'affiliates' => $affiliates,
        ]);
    }

    private function resolveAuthenticatedVendor(Request $request): Vendor
    {
        $authenticatedVendor = $request->user()?->vendor;
        abort_if($authenticatedVendor === null, 403, 'Vendor account is required.');
        return $authenticatedVendor;
    }
}

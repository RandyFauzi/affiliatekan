<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SsoController extends Controller
{
    public function generate(Request $request): JsonResponse
    {
        $vendorId = $request->attributes->get('vendor_id');

        $token = (string) Str::uuid();

        // Save token to Cache with a 60-second TTL
        Cache::put('sso_token_' . $token, $vendorId, 60);

        return response()->json([
            'sso_url' => url('/sso-login?token=' . $token),
        ]);
    }
}

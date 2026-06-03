<?php

namespace App\Http\Middleware;

use App\Models\Vendor;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VendorApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $providedApiKey = $request->header('X-API-KEY');

        if (blank($providedApiKey)) {
            return $this->buildUnauthorizedResponse();
        }

        $vendor = Vendor::query()
            ->where('api_key_hash', hash('sha256', $providedApiKey))
            ->first();

        if ($vendor === null) {
            return $this->buildUnauthorizedResponse();
        }

        $request->attributes->add([
            'vendor_id' => $vendor->id,
        ]);

        return $next($request);
    }

    private function buildUnauthorizedResponse(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized.',
        ], 401);
    }
}

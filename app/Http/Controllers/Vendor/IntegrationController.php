<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IntegrationController extends Controller
{
    public function index(Request $request): View
    {
        $authenticatedVendor = $this->resolveAuthenticatedVendor($request);
        $publicTrackingSnippet = <<<HTML
<script src="https://affiliatekan.com/tracker.js" data-cookie-duration="{$authenticatedVendor->cookie_duration_days}"></script>
HTML;
        $nativePhpCurlExample = <<<PHP
<?php

// Jalankan setelah sistem pembayaran Anda menandai order sebagai LUNAS.
\$payload = json_encode([
    'order_id' => \$order->invoice_number,
    'amount' => (int) \$order->total_price,
    'ref_code' => \$_COOKIE['affiliatekan_ref'] ?? null,
]);

\$curlHandle = curl_init('https://api.affiliatekan.com/v1/conversions');

curl_setopt_array(\$curlHandle, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Accept: application/json',
        'X-API-KEY: {$authenticatedVendor->api_key}',
    ],
    CURLOPT_POSTFIELDS => \$payload,
]);

\$responseBody = curl_exec(\$curlHandle);
\$responseStatus = curl_getinfo(\$curlHandle, CURLINFO_HTTP_CODE);

if (\$responseBody === false) {
    throw new RuntimeException(curl_error(\$curlHandle));
}

curl_close(\$curlHandle);
PHP;
        $laravelHttpClientExample = <<<PHP
<?php

use Illuminate\Support\Facades\Http;

// Jalankan setelah callback Midtrans / GoPay / gateway lain menyatakan pembayaran sukses.
\$response = Http::withHeaders([
    'X-API-KEY' => '{$authenticatedVendor->api_key}',
])->post('https://api.affiliatekan.com/v1/conversions', [
    'order_id' => \$order->invoice_number,
    'amount' => (int) \$order->total_price,
    'ref_code' => \$request->cookie('affiliatekan_ref'),
]);

if (\$response->failed()) {
    report(new RuntimeException('Affiliatekan conversion push failed: ' . \$response->body()));
}
PHP;

        return view('vendor.integration.index', [
            'authenticatedVendor' => $authenticatedVendor,
            'publicTrackingSnippet' => $publicTrackingSnippet,
            'nativePhpCurlExample' => $nativePhpCurlExample,
            'laravelHttpClientExample' => $laravelHttpClientExample,
        ]);
    }

    public function updateSettings(Request $request): \Illuminate\Http\RedirectResponse
    {
        $authenticatedVendor = $this->resolveAuthenticatedVendor($request);

        $validatedPayload = $request->validate([
            'website_url' => ['required', 'url', 'max:255'],
            'webhook_url' => ['nullable', 'url', 'max:255'],
            'cookie_duration_days' => ['required', 'integer', 'min:1', 'max:365'],
        ]);

        $authenticatedVendor->update($validatedPayload);

        \App\Services\AuditLogger::log('vendor.settings.update', [
            'vendor_id' => $authenticatedVendor->id,
            'website_url' => $validatedPayload['website_url'] ?? null,
            'webhook_url' => $validatedPayload['webhook_url'] ?? null,
            'cookie_duration_days' => (int) ($validatedPayload['cookie_duration_days'] ?? 30),
        ]);

        return back()->with('status', 'Pengaturan integrasi berhasil disimpan.');
    }

    private function resolveAuthenticatedVendor(Request $request): Vendor
    {
        $authenticatedVendor = $request->user()?->vendor;

        abort_if($authenticatedVendor === null, 403, 'Vendor account is required to access this page.');

        return $authenticatedVendor;
    }
}

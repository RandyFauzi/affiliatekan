<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Tampilkan halaman daftar produk & komisi rujukan yang ditarik live dari website tenant.
     */
    public function index(Request $request): View
    {
        $vendor = $request->user()?->vendor;
        abort_if($vendor === null, 403, 'Vendor account is required to access this page.');

        $packages = [];
        $error = null;

        if (blank($vendor->website_url) || blank($vendor->api_key)) {
            $error = 'Website URL dan API Key belum dikonfigurasi. Silakan lengkapi halaman Integrasi terlebih dahulu.';
        } else {
            try {
                // Tembak endpoint API produk & komisi di sisi Langitara secara live
                $packagesUrl = rtrim($vendor->website_url, '/') . '/api/v1/affiliate/packages';
                
                $response = Http::withHeaders([
                    'X-API-KEY' => $vendor->api_key,
                    'Accept'    => 'application/json',
                ])->timeout(8)->get($packagesUrl);

                if ($response->successful()) {
                    $packages = $response->json();
                } else {
                    $error = 'Gagal memuat produk dari website tenant. Server merespons dengan status ' . $response->status() . '.';
                    Log::warning('[Affiliatekan Product Pull] Gagal mengambil data paket', [
                        'vendor_id' => $vendor->id,
                        'url' => $packagesUrl,
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                }
            } catch (\Exception $e) {
                $error = 'Tidak dapat menghubungi server website tenant. Pastikan alamat website aktif dan dapat diakses.';
                Log::error('[Affiliatekan Product Pull] Exception saat menghubungi server tenant: ' . $e->getMessage());
            }
        }

        return view('vendor.products.index', [
            'authenticatedVendor' => $vendor,
            'packages' => $packages,
            'error' => $error,
        ]);
    }
}

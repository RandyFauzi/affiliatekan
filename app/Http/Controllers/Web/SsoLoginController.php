<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SsoLoginController extends Controller
{
    public function verify(Request $request): RedirectResponse
    {
        $token = $request->query('token');

        if (blank($token)) {
            return redirect()->route('login')->withErrors([
                'sso' => 'Sesi SSO tidak valid atau kedaluwarsa.',
            ]);
        }

        $vendorId = Cache::get('sso_token_' . $token);

        if ($vendorId === null) {
            return redirect()->route('login')->withErrors([
                'sso' => 'Sesi SSO tidak valid atau kedaluwarsa.',
            ]);
        }

        $vendor = Vendor::query()->with('user')->find($vendorId);

        if ($vendor === null || $vendor->user === null) {
            return redirect()->route('login')->withErrors([
                'sso' => 'Akun vendor tidak ditemukan.',
            ]);
        }

        // Burn the token immediately to ensure one-time usage
        Cache::forget('sso_token_' . $token);

        // Authenticate the user
        Auth::login($vendor->user);

        return redirect()->route('vendor.dashboard.index');
    }
}

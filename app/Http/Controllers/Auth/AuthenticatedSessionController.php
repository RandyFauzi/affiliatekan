<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectToDashboard(Auth::user()->role);
        }

        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedCredentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($validatedCredentials, $request->boolean('remember'))) {
            return back()
                ->withErrors([
                    'email' => 'Email atau password tidak valid.',
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return $this->redirectToDashboard((string) $request->user()->role);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirectToDashboard(string $userRole): RedirectResponse
    {
        if ($userRole === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($userRole === 'vendor') {
            return redirect()->route('vendor.dashboard.index');
        }

        if ($userRole === 'affiliate') {
            return redirect()->route('affiliate.dashboard.index');
        }

        return redirect()->route('login');
    }
}

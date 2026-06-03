<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePublicAccountRegistrationRequest;
use App\Models\User;
use App\Services\TrackingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PublicAccountRegistrationController extends Controller
{
    public function __construct(
        private readonly TrackingService $trackingService
    ) {
    }

    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    public function storeRegisteredAccount(StorePublicAccountRegistrationRequest $request): RedirectResponse
    {
        $validatedRegistrationPayload = $request->validated();

        $registeredUser = DB::transaction(function () use ($validatedRegistrationPayload): User {
            $registeredUser = User::query()->create([
                'name' => $validatedRegistrationPayload['name'],
                'email' => $validatedRegistrationPayload['email'],
                'password' => Hash::make($validatedRegistrationPayload['password']),
                'role' => $validatedRegistrationPayload['role'],
            ]);

            if ($validatedRegistrationPayload['role'] === 'vendor') {
                $registeredUser->vendor()->create([
                    'company_name' => $validatedRegistrationPayload['company_name'],
                    'website_url' => $validatedRegistrationPayload['website_url'] ?? null,
                    'commission_type' => 'percentage',
                    'commission_value' => 10,
                    'cookie_duration_days' => 30,
                ]);

                return $registeredUser;
            }

            $registeredUser->affiliate()->create([
                'referral_code' => $this->trackingService->generateAffiliateCode(),
            ]);

            return $registeredUser;
        });

        Auth::login($registeredUser);

        return $registeredUser->role === 'vendor'
            ? redirect()->route('vendor.dashboard.index')
            : redirect()->route('affiliate.dashboard.index');
    }
}

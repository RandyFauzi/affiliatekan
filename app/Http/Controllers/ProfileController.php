<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $user = auth()->user()->load(['vendor', 'affiliate']);
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];

        // Conditional validation based on role
        if ($user->role === 'vendor') {
            $rules['company_name'] = ['required', 'string', 'max:255'];
            $rules['website_url'] = ['nullable', 'url', 'max:255'];
            $rules['commission_type'] = ['required', 'string', 'in:flat,percentage'];
            $rules['commission_value'] = ['required', 'numeric', 'min:0'];
            $rules['cookie_duration_days'] = ['required', 'integer', 'min:1', 'max:365'];
        } elseif ($user->role === 'affiliate') {
            $rules['bank_name'] = ['nullable', 'string', 'max:255'];
            $rules['bank_account_number'] = ['nullable', 'string', 'max:255'];
            $rules['bank_account_name'] = ['nullable', 'string', 'max:255'];
        }

        $validated = $request->validate($rules);

        // Update core user data
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Update role-specific data
        if ($user->role === 'vendor' && $user->vendor) {
            $user->vendor->update([
                'company_name' => $validated['company_name'],
                'website_url' => $validated['website_url'] ?? null,
                'commission_type' => $validated['commission_type'],
                'commission_value' => $validated['commission_value'],
                'cookie_duration_days' => $validated['cookie_duration_days'],
            ]);
        } elseif ($user->role === 'affiliate' && $user->affiliate) {
            $user->affiliate->update([
                'bank_name' => $validated['bank_name'] ?? null,
                'bank_account_number' => $validated['bank_account_number'] ?? null,
                'bank_account_name' => $validated['bank_account_name'] ?? null,
            ]);
        }

        // Log the audit event
        \App\Services\AuditLogger::log('profile.update', [
            'role' => $user->role,
            'fields_updated' => array_keys($validated),
        ]);

        return redirect()
            ->route('profile.edit')
            ->with('status', 'Profil Anda berhasil diperbarui.');
    }
}

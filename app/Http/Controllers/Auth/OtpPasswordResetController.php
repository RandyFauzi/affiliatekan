<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordOtpMail;
use App\Models\PasswordResetOtp;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OtpPasswordResetController extends Controller
{
    private const OTP_EXPIRATION_MINUTES = 10;
    private const OTP_RESEND_COOLDOWN_SECONDS = 60;

    public function showEmailRequestForm(): View
    {
        return view('auth.password-reset.request-email');
    }

    public function sendPasswordResetOtp(Request $request): RedirectResponse
    {
        $validatedPayload = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $normalizedEmail = Str::lower($validatedPayload['email']);
        $resendCooldownCacheKey = $this->buildOtpResendCooldownCacheKey($normalizedEmail);

        if (! Cache::add($resendCooldownCacheKey, true, self::OTP_RESEND_COOLDOWN_SECONDS)) {
            return back()
                ->withErrors(['email' => 'Kode OTP baru bisa dikirim ulang setiap 60 detik.'])
                ->onlyInput('email');
        }

        $request->session()->put('password_reset_email', $normalizedEmail);

        if (User::query()->where('email', $normalizedEmail)->exists()) {
            $plainOtpCode = (string) random_int(100000, 999999);

            PasswordResetOtp::query()
                ->where('email', $normalizedEmail)
                ->delete();

            PasswordResetOtp::query()->create([
                'email' => $normalizedEmail,
                'otp' => Hash::make($plainOtpCode),
                'expires_at' => now()->addMinutes(self::OTP_EXPIRATION_MINUTES),
            ]);

            Mail::to($normalizedEmail)->queue(new ResetPasswordOtpMail($plainOtpCode));
        }

        return redirect()
            ->route('password.otp.verify.form')
            ->with('status', 'Jika email terdaftar, kode OTP sudah kami kirim.');
    }

    public function showOtpVerificationForm(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('password_reset_email')) {
            return redirect()->route('password.otp.request');
        }

        return view('auth.password-reset.verify-otp', [
            'maskedEmail' => $this->maskEmail((string) $request->session()->get('password_reset_email')),
        ]);
    }

    public function verifyPasswordResetOtp(Request $request): RedirectResponse
    {
        if (! $request->session()->has('password_reset_email')) {
            return redirect()->route('password.otp.request');
        }

        $validatedPayload = $request->validate([
            'otp_digits' => ['required', 'array', 'size:6'],
            'otp_digits.*' => ['required', 'digits:1'],
        ]);

        $normalizedEmail = (string) $request->session()->get('password_reset_email');
        $submittedOtpCode = implode('', $validatedPayload['otp_digits']);

        $passwordResetOtp = PasswordResetOtp::query()
            ->where('email', $normalizedEmail)
            ->where('expires_at', '>=', now())
            ->latest('created_at')
            ->first();

        if ($passwordResetOtp === null || ! Hash::check($submittedOtpCode, $passwordResetOtp->otp)) {
            return back()->withErrors(['otp_digits' => 'Kode OTP tidak valid atau sudah kedaluwarsa.']);
        }

        $passwordResetOtp->delete();

        $request->session()->put([
            'password_reset_verified_email' => $normalizedEmail,
            'password_reset_authorization_token' => Str::random(48),
        ]);

        return redirect()->route('password.otp.reset.form');
    }

    public function showNewPasswordForm(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('password_reset_verified_email')) {
            return redirect()->route('password.otp.request');
        }

        return view('auth.password-reset.new-password');
    }

    public function storeNewPassword(Request $request): RedirectResponse
    {
        if (! $request->session()->has('password_reset_verified_email')) {
            return redirect()->route('password.otp.request');
        }

        $validatedPayload = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $verifiedEmail = (string) $request->session()->get('password_reset_verified_email');

        User::query()
            ->where('email', $verifiedEmail)
            ->update([
                'password' => Hash::make($validatedPayload['password']),
            ]);

        $request->session()->forget([
            'password_reset_email',
            'password_reset_verified_email',
            'password_reset_authorization_token',
        ]);

        return redirect()
            ->route('login')
            ->with('status', 'Password baru berhasil disimpan. Silakan login.');
    }

    private function buildOtpResendCooldownCacheKey(string $normalizedEmail): string
    {
        return 'password-reset-otp-resend:' . hash('sha256', $normalizedEmail);
    }

    private function maskEmail(string $email): string
    {
        [$emailName, $emailDomain] = array_pad(explode('@', $email, 2), 2, '');

        return Str::limit($emailName, 2, '') . str_repeat('*', max(strlen($emailName) - 2, 3)) . '@' . $emailDomain;
    }
}

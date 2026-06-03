<?php

namespace Tests\Feature;

use App\Mail\ResetPasswordOtpMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OtpPasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_reset_password_with_queued_otp(): void
    {
        Mail::fake();

        $registeredUser = User::factory()->create([
            'email' => 'reset-target@example.com',
            'password' => Hash::make('old-password'),
            'role' => 'affiliate',
        ]);

        $sendOtpResponse = $this->post(route('password.otp.send'), [
            'email' => $registeredUser->email,
        ]);

        $plainOtpCode = null;

        $sendOtpResponse->assertRedirect(route('password.otp.verify.form'));
        $this->assertDatabaseHas('password_reset_otps', [
            'email' => $registeredUser->email,
        ]);
        Mail::assertQueued(ResetPasswordOtpMail::class, function (ResetPasswordOtpMail $mailable) use (&$plainOtpCode): bool {
            $plainOtpCode = $mailable->plainOtpCode;

            return preg_match('/^\d{6}$/', $mailable->plainOtpCode) === 1;
        });

        $verifyOtpResponse = $this->post(route('password.otp.verify'), [
            'otp_digits' => str_split((string) $plainOtpCode),
        ]);

        $verifyOtpResponse->assertRedirect(route('password.otp.reset.form'));
        $this->assertDatabaseMissing('password_reset_otps', [
            'email' => $registeredUser->email,
        ]);

        $resetPasswordResponse = $this->post(route('password.otp.reset'), [
            'password' => 'new-password123',
            'password_confirmation' => 'new-password123',
        ]);

        $resetPasswordResponse->assertRedirect(route('login'));
        $this->assertTrue(Hash::check('new-password123', $registeredUser->refresh()->password));
    }

    public function test_otp_cannot_be_reused_after_successful_verification(): void
    {
        Mail::fake();

        $registeredUser = User::factory()->create([
            'email' => 'single-use@example.com',
            'role' => 'affiliate',
        ]);

        $this->post(route('password.otp.send'), [
            'email' => $registeredUser->email,
        ]);

        $plainOtpCode = null;

        Mail::assertQueued(ResetPasswordOtpMail::class, function (ResetPasswordOtpMail $mailable) use (&$plainOtpCode): bool {
            $plainOtpCode = $mailable->plainOtpCode;

            return true;
        });

        $this->post(route('password.otp.verify'), [
            'otp_digits' => str_split((string) $plainOtpCode),
        ])->assertRedirect(route('password.otp.reset.form'));

        $this
            ->withSession(['password_reset_email' => $registeredUser->email])
            ->post(route('password.otp.verify'), [
                'otp_digits' => str_split((string) $plainOtpCode),
            ])
            ->assertSessionHasErrors('otp_digits');
    }
}

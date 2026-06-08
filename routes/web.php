<?php

use App\Http\Controllers\Admin\AffiliateController as AdminAffiliateController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\VendorController as AdminVendorController;
use App\Http\Controllers\Affiliate\DashboardController;
use App\Http\Controllers\Affiliate\PayoutRequestController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\OtpPasswordResetController;
use App\Http\Controllers\Auth\PublicAccountRegistrationController;
use App\Http\Controllers\Vendor\DashboardController as VendorDashboardController;
use App\Http\Controllers\Vendor\IntegrationController;
use App\Http\Controllers\Vendor\PayoutController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (auth()->check()) {
        $authenticatedUser = auth()->user();

        if ($authenticatedUser->role === 'vendor') {
            return redirect()->route('vendor.dashboard.index');
        }

        if ($authenticatedUser->role === 'affiliate') {
            return redirect()->route('affiliate.dashboard.index');
        }

        if ($authenticatedUser->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
    }

    return view('welcome');
});

Route::get('/sso-login', [App\Http\Controllers\Web\SsoLoginController::class, 'verify'])
    ->name('sso.verify');

Route::middleware('guest')
    ->group(function (): void {
        Route::get('/login', [AuthenticatedSessionController::class, 'create'])
            ->name('login');

        Route::post('/login', [AuthenticatedSessionController::class, 'store'])
            ->name('login.store');

        Route::get('/register', [PublicAccountRegistrationController::class, 'showRegistrationForm'])
            ->name('register');

        Route::post('/register', [PublicAccountRegistrationController::class, 'storeRegisteredAccount'])
            ->name('register.store');

        Route::get('/forgot-password', [OtpPasswordResetController::class, 'showEmailRequestForm'])
            ->name('password.otp.request');

        Route::post('/forgot-password', [OtpPasswordResetController::class, 'sendPasswordResetOtp'])
            ->middleware('throttle:3,1')
            ->name('password.otp.send');

        Route::get('/forgot-password/verify', [OtpPasswordResetController::class, 'showOtpVerificationForm'])
            ->name('password.otp.verify.form');

        Route::post('/forgot-password/verify', [OtpPasswordResetController::class, 'verifyPasswordResetOtp'])
            ->middleware('throttle:10,1')
            ->name('password.otp.verify');

        Route::get('/forgot-password/reset', [OtpPasswordResetController::class, 'showNewPasswordForm'])
            ->name('password.otp.reset.form');

        Route::post('/forgot-password/reset', [OtpPasswordResetController::class, 'storeNewPassword'])
            ->name('password.otp.reset');
    });

Route::middleware('auth')
    ->group(function (): void {
        Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])
            ->name('profile.edit');
        Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])
            ->name('profile.update');

        Route::prefix('admin')
            ->middleware('admin')
            ->name('admin.')
            ->group(function (): void {
                Route::get('/dashboard', [AdminDashboardController::class, 'index'])
                    ->name('dashboard');

                Route::get('/vendors', [AdminVendorController::class, 'index'])
                    ->name('vendors.index');

                Route::post('/vendors', [AdminVendorController::class, 'store'])
                    ->name('vendors.store');

                Route::put('/vendors/{vendor}', [AdminVendorController::class, 'update'])
                    ->name('vendors.update');

                Route::delete('/vendors/{vendor}', [AdminVendorController::class, 'destroy'])
                    ->name('vendors.destroy');

                Route::get('/affiliates', [AdminAffiliateController::class, 'index'])
                    ->name('affiliates.index');

                Route::post('/affiliates', [AdminAffiliateController::class, 'store'])
                    ->name('affiliates.store');

                Route::put('/affiliates/{affiliate}', [AdminAffiliateController::class, 'update'])
                    ->name('affiliates.update');

                Route::delete('/affiliates/{affiliate}', [AdminAffiliateController::class, 'destroy'])
                    ->name('affiliates.destroy');
            });

        Route::middleware('affiliate')
            ->group(function (): void {
                Route::get('/affiliate/dashboard', [DashboardController::class, 'index'])
                    ->name('affiliate.dashboard.index');

                Route::get('/affiliate/transactions', [\App\Http\Controllers\Affiliate\TransactionController::class, 'index'])
                    ->name('affiliate.transactions.index');

                Route::get('/affiliate/programs', [App\Http\Controllers\Affiliate\ProgramController::class, 'index'])
                    ->name('affiliate.programs.index');

                Route::post('/affiliate/programs/{vendor}/join', [App\Http\Controllers\Affiliate\ProgramController::class, 'join'])
                    ->name('affiliate.programs.join');

                Route::get('/affiliate/payouts', [PayoutRequestController::class, 'index'])
                    ->name('affiliate.payouts.index');

                Route::put('/affiliate/bank-account', [PayoutRequestController::class, 'updateBankAccount'])
                    ->name('affiliate.bank-account.update');

                Route::post('/affiliate/payout-requests', [PayoutRequestController::class, 'store'])
                    ->name('affiliate.payout-requests.store');
            });

        Route::middleware('vendor')
            ->prefix('tenant')
            ->group(function (): void {
                Route::get('/dashboard', [VendorDashboardController::class, 'index'])
                    ->name('vendor.dashboard.index');

                Route::get('/packages', [App\Http\Controllers\Vendor\ProductController::class, 'index'])
                    ->name('vendor.products.index');

                Route::get('/affiliates', [App\Http\Controllers\Vendor\AffiliateListController::class, 'index'])
                    ->name('vendor.affiliates.index');

                Route::get('/integration', [IntegrationController::class, 'index'])
                    ->name('vendor.integration.index');

                Route::put('/integration/settings', [IntegrationController::class, 'updateSettings'])
                    ->name('vendor.integration.settings.update');

                Route::get('/integration-guide', [IntegrationController::class, 'index'])
                    ->name('vendor.integration.guide');

                Route::get('/payouts', [PayoutController::class, 'index'])
                    ->name('vendor.payouts.index');

                Route::post('/payouts/{payoutId}/pay-manual', [PayoutController::class, 'payManual'])
                    ->name('vendor.payouts.pay-manual');

                Route::post('/payouts/{payoutId}/process', [PayoutController::class, 'markAsProcessing'])
                    ->name('vendor.payouts.process');
            });


        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
            ->name('logout');
    });

if (app()->environment('local')) {
    Route::get('/mail-preview/payout-requested-vendor', function () {
        $payout = \App\Models\Payout::with(['affiliate.user', 'vendor'])->first();
        if (!$payout) {
            $payout = new \App\Models\Payout(['amount' => 150000.00, 'status' => 'requested']);
            $vendor = new \App\Models\Vendor(['company_name' => 'Toko Onfix']);
            $user = new \App\Models\User(['name' => 'Afiliator Jago', 'email' => 'aff@test.com']);
            $affiliate = new \App\Models\Affiliate(['referral_code' => 'aff_jago', 'bank_name' => 'Bank Mandiri', 'bank_account_number' => '1234567890', 'bank_account_name' => 'Afiliator Jago']);
            $affiliate->setRelation('user', $user);
            $payout->setRelation('vendor', $vendor);
            $payout->setRelation('affiliate', $affiliate);
        }
        return new \App\Mail\PayoutRequestedForVendor($payout);
    });

    Route::get('/mail-preview/payout-requested-affiliate', function () {
        $payout = \App\Models\Payout::with(['affiliate.user', 'vendor'])->first();
        if (!$payout) {
            $payout = new \App\Models\Payout(['amount' => 150000.00, 'status' => 'requested']);
            $vendor = new \App\Models\Vendor(['company_name' => 'Toko Onfix']);
            $user = new \App\Models\User(['name' => 'Afiliator Jago', 'email' => 'aff@test.com']);
            $affiliate = new \App\Models\Affiliate(['referral_code' => 'aff_jago', 'bank_name' => 'Bank Mandiri', 'bank_account_number' => '1234567890', 'bank_account_name' => 'Afiliator Jago']);
            $affiliate->setRelation('user', $user);
            $payout->setRelation('vendor', $vendor);
            $payout->setRelation('affiliate', $affiliate);
        }
        return new \App\Mail\PayoutRequestedForAffiliate($payout);
    });

    Route::get('/mail-preview/payout-completed-affiliate', function () {
        $payout = \App\Models\Payout::with(['affiliate.user', 'vendor'])->first();
        if (!$payout) {
            $payout = new \App\Models\Payout(['amount' => 150000.00, 'status' => 'paid']);
            $vendor = new \App\Models\Vendor(['company_name' => 'Toko Onfix']);
            $user = new \App\Models\User(['name' => 'Afiliator Jago', 'email' => 'aff@test.com']);
            $affiliate = new \App\Models\Affiliate(['referral_code' => 'aff_jago', 'bank_name' => 'Bank Mandiri', 'bank_account_number' => '1234567890', 'bank_account_name' => 'Afiliator Jago']);
            $affiliate->setRelation('user', $user);
            $payout->setRelation('vendor', $vendor);
            $payout->setRelation('affiliate', $affiliate);
        }
        return new \App\Mail\PayoutCompletedForAffiliate($payout, 'http://localhost/storage/payout-proofs/dummy.png');
    });
}

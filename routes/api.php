<?php

use App\Http\Controllers\Api\ClickController;
use App\Http\Controllers\Api\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')
    ->group(function (): void {
        Route::post('/clicks', [ClickController::class, 'store'])
            ->name('api.v1.clicks.store');

        Route::middleware('vendor.api.key')
            ->group(function (): void {
                Route::post('/conversions', [WebhookController::class, 'store'])
                    ->name('api.v1.conversions.store');

                Route::get('/vendor/stats', \App\Http\Controllers\Api\Vendor\StatsController::class)
                    ->name('api.v1.vendor.stats');

                Route::post('/vendor/sso-ticket', [\App\Http\Controllers\Api\Vendor\SsoController::class, 'generate'])
                    ->name('api.v1.vendor.sso-ticket');
            });
    });

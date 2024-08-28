<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        DB::listen(function ($query) {
            Log::info($query->sql, $query->bindings);
        });

        Passport::enablePasswordGrant();

        // Mengatur masa berlaku token access dan refresh token menjadi sebulan
        Passport::tokensExpireIn(now()->addMonths(1)); // Access token expired in 1 month
        Passport::refreshTokensExpireIn(now()->addMonths(1)); // Refresh token expired in 1 month

        // Mengatur masa berlaku token personal menjadi sebulan
        Passport::personalAccessTokensExpireIn(now()->addMonths(1)); // Personal Access Token expired in 1 month
    }
}

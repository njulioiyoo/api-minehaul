<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Device;
use App\Policies\DevicePolicy;
use Illuminate\Support\Facades\Gate;
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
        Passport::enablePasswordGrant();
        Gate::policy(Device::class, DevicePolicy::class);
    }
}

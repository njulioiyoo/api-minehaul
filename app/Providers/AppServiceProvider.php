<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Device;
use App\Policies\DevicePolicy;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
        // Mengatur masa berlaku token access dan refresh token
        Passport::tokensExpireIn(now()->addHours(1)); // Access token expired in 1 hour
        Passport::refreshTokensExpireIn(now()->addDays(3)); // Refresh token expired in 3 days

        // Mengatur masa berlaku token personal
        Passport::personalAccessTokensExpireIn(now()->addDays(7)); // Personal Access Token expired in 7 days

        Gate::policy(Device::class, DevicePolicy::class);
        Gate::policy(Permission::class, PermissionPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);
    }
}

<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Device;
use App\Models\Menu;
use App\Models\User;
use App\Policies\DevicePolicy;
use App\Policies\MenuPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
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
        DB::listen(function ($query) {
            Log::info($query->sql, $query->bindings);
        });

        Passport::enablePasswordGrant();

        // Mengatur masa berlaku token access dan refresh token menjadi sebulan
        Passport::tokensExpireIn(now()->addMonths(1)); // Access token expired in 1 month
        Passport::refreshTokensExpireIn(now()->addMonths(1)); // Refresh token expired in 1 month

        // Mengatur masa berlaku token personal menjadi sebulan
        Passport::personalAccessTokensExpireIn(now()->addMonths(1)); // Personal Access Token expired in 1 month

        // Gate::policy(Device::class, DevicePolicy::class);
        // Gate::policy(Menu::class, MenuPolicy::class);
        // Gate::policy(Permission::class, PermissionPolicy::class);
        // Gate::policy(Role::class, RolePolicy::class);
        // Gate::policy(User::class, UserPolicy::class);
    }
}

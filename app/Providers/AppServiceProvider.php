<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Device;
use App\Policies\DevicePolicy;
use App\Policies\PermissionPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Spatie\Permission\Models\Permission;

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
        Gate::policy(Permission::class, PermissionPolicy::class);
    }
}

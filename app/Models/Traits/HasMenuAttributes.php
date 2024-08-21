<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\PermissionMenu;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

trait HasMenuAttributes
{
    public static function bootHasMenuAttributes()
    {
        static::saving(function ($menu) {
            $menu->generateKey();
            $menu->captureRoles();
        });

        static::saved(function ($menu) {
            $menu->syncRolesAndPermissions();
        });
    }

    protected function generateKey()
    {
        $this->key = Str::slug($this->name);
    }

    protected function captureRoles()
    {
        if (request()->has('data.attributes.roles')) {
            $this->rolesToSync = request()->input('data.attributes.roles');
        }
    }

    protected function syncRolesAndPermissions()
    {
        if (! empty($this->rolesToSync) && is_array($this->rolesToSync)) {
            $this->roles()->sync($this->rolesToSync);
            $this->syncPermissions();
        }
    }

    protected function syncPermissions()
    {
        $permissions = Role::whereIn('id', $this->rolesToSync)->with('permissions')->get()
            ->pluck('permissions')
            ->flatten()
            ->unique('id')
            ->mapWithKeys(function ($permission) {
                return [$permission->id => 'read'];
            })
            ->toArray();

        foreach ($permissions as $permissionId => $status) {
            $this->syncPermission($permissionId, $status);
        }
    }

    protected function syncPermission($permissionId, $status)
    {
        try {
            PermissionMenu::updateOrCreate(
                ['menu_id' => $this->id, 'permission_id' => $permissionId],
                ['status' => $status]
            );
        } catch (\Exception $e) {
            Log::error('Error syncing PermissionMenu:', [
                'menu_id' => $this->id,
                'permission_id' => $permissionId,
                'status' => $status,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

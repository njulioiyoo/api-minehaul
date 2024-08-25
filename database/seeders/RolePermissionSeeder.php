<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User; // Pastikan model User Anda ada di namespace ini

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch or create roles
        $accountRole = Role::firstOrCreate(['name' => 'Account', 'guard_name' => 'api']);
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Administrator', 'guard_name' => 'api']);

        // Define permissions for Account
        $accountPermissions = [
            'View Dashboard',
            'View Tracking',
            'View Navigation',
            'Create Navigation',
            'Edit Navigation',
            'Delete Navigation',
            'View Alerts',
            'View Reports',
            'View Reports History',
            'View Vehicle',
            'Create Vehicle',
            'Edit Vehicle',
            'Delete Vehicle',
            'View Device',
            'Create Device',
            'Edit Device',
            'Delete Device',
            'View Driver',
            'Create Driver',
            'Edit Driver',
            'Delete Driver',
            'View Tags',
            'View Notifications',
            'View Minehaul AI',
        ];

        // Define permissions for Super Administrator
        $superAdminPermissions = array_merge($accountPermissions, [
            'View Users',
            'Create Users',
            'Edit Users',
            'Delete Users',
            'View Roles',
            'Create Roles',
            'Edit Roles',
            'Delete Roles',
            'View Menus',
            'Edit Menus',
        ]);

        // Create permissions if they don't exist
        foreach (array_unique(array_merge($accountPermissions, $superAdminPermissions)) as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'api']);
        }

        // Fetch created permissions from database
        $accountPermissions = Permission::whereIn('name', $accountPermissions)->get();
        $superAdminPermissions = Permission::whereIn('name', $superAdminPermissions)->get();

        // Assign permissions to roles
        $accountRole->syncPermissions($accountPermissions);
        $superAdminRole->syncPermissions($superAdminPermissions);

        // Define an associative array of users and their corresponding roles
        $usersWithRoles = [
            'super_administrator' => $superAdminRole,
            'accountan' => $accountRole,
        ];

        // Loop through each user and assign the corresponding role
        foreach ($usersWithRoles as $username => $role) {
            $user = User::where('username', $username)->first();

            if ($user) {
                $user->assignRole($role);
            }
        }
    }
}

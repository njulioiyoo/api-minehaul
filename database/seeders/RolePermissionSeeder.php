<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Pit; // Assuming you have a Pit model
use App\Models\User; // Assuming you have an Account model
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch or create roles
        $administratorUser = Role::firstOrCreate(['name' => 'Administrator User', 'guard_name' => 'api']);
        $regularUser = Role::firstOrCreate(['name' => 'Regular User', 'guard_name' => 'api']);
        $superAccount = Role::firstOrCreate(['name' => 'Super Account', 'guard_name' => 'api']);
        $superUser = Role::firstOrCreate(['name' => 'Super User', 'guard_name' => 'api']);

        // Define permissions for Regular User
        $regularUserPermissions = [
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
            'Show Vehicle',
            'Create Vehicle',
            'Edit Vehicle',
            'Delete Vehicle',
            'View Device',
            'Show Device',
            'Create Device',
            'Edit Device',
            'Delete Device',
            'View Driver',
            'Show Driver',
            'Create Driver',
            'Edit Driver',
            'Delete Driver',
            'View Location',
            'Show Location',
            'Create Location',
            'Edit Location',
            'Delete Location',
            'View Tags',
            'View Notifications',
            'View Minehaul AI',
        ];

        $slugRegularUserPermissions = collect($regularUserPermissions)->map(function ($permission) {
            return Str::slug($permission);
        });

        // Define permissions for Administrator User (includes all regular user permissions)
        $administratorPermissions = array_merge($regularUserPermissions, [
            'View Users',
            'Create Users',
            'Edit Users',
            'Delete Users',
            'Show Users',
            'View Roles',
            'Create Roles',
            'Edit Roles',
            'Delete Roles',
            'Show Roles',
            'View Permissions',
            'Create Permissions',
            'Edit Permissions',
            'Delete Permissions',
            'Show Permissions',
            'View Menus',
            'Create Menus',
            'Edit Menus',
            'Delete Menus',
            'Show Menus',
        ]);

        $slugAdminPermissions = collect($administratorPermissions)->map(function ($permission) {
            return Str::slug($permission);
        });

        // Define permissions for Super Account role
        $superAccountPermissions = [
            'View Account Onboarding',
            'Manage All Accounts',
            'Activate All Features',
            'Manage Account Billing',
        ];

        $slugSuperAccountPermissions = collect($superAccountPermissions)->map(function ($permission) {
            return Str::slug($permission);
        });

        // Define permissions for Super User role (same as Super Account role)
        $superUserPermissions = $superAccountPermissions;

        $slugSuperUserPermissions = collect($superUserPermissions)->map(function ($permission) {
            return Str::slug($permission);
        });

        // Create permissions if they don't exist
        foreach (
            $slugRegularUserPermissions
                ->merge($slugAdminPermissions)
                ->merge($slugSuperAccountPermissions)
                ->merge($slugSuperUserPermissions)
                ->unique() as $permissionName
        ) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'api']);
        }

        // Fetch created permissions from the database
        $regularPermissions = Permission::whereIn('name', $slugRegularUserPermissions)->get();
        $adminPermissions = Permission::whereIn('name', $slugAdminPermissions)->get();
        $superAccountPermissions = Permission::whereIn('name', $slugSuperAccountPermissions)->get();
        $superUserPermissions = Permission::whereIn('name', $slugSuperUserPermissions)->get();

        // Sync permissions to roles
        $regularUser->syncPermissions($regularPermissions);
        $administratorUser->syncPermissions($adminPermissions);
        $superAccount->syncPermissions($superAccountPermissions);
        $superUser->syncPermissions($superUserPermissions);

        // Assign users to roles
        $usersWithRoles = [
            'administrator_user' => $administratorUser,
            'regular_user' => $regularUser,
            'super_account' => $superAccount,
            'super_user' => $superUser,
        ];

        foreach ($usersWithRoles as $username => $role) {
            $user = User::where('username', $username)->first();

            if ($user) {
                $user->syncRoles([$role]);
                $user->syncPermissions($role->permissions); // Ensure permissions are also synced
            }
        }

        // Insert data into role_has_pits table
        $roles = Role::all();
        $accounts = Account::all(); // Assuming you have an Account model
        $pits = Pit::all(); // Assuming you have a Pit model

        foreach ($roles as $role) {
            foreach ($accounts as $account) {
                foreach ($pits as $pit) {
                    DB::table('role_has_pits')->insert([
                        'role_id' => $role->id,
                        'account_id' => $account->id,
                        'pit_id' => $pit->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}

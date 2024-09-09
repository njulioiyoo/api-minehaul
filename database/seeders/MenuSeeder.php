<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch roles
        $administratorUser = Role::where('name', 'Administrator User')->first();
        $regularUser = Role::where('name', 'Regular User')->first();
        $superAccount = Role::where('name', 'Super Account')->first();
        $superUser = Role::where('name', 'Super User')->first();

        // Fetch permissions
        $permissions = Permission::all();
        $permissionIds = $permissions->pluck('id', 'name');

        // Insert main menus
        $menus = [
            ['name' => 'Dashboard', 'icon' => 'dashboard_icon', 'url' => '', 'position' => 1],
            ['name' => 'Tracking', 'icon' => 'tracking_icon', 'url' => '', 'position' => 2],
            ['name' => 'Navigation', 'icon' => 'navigation_icon', 'url' => '', 'position' => 3],
            ['name' => 'Alerts', 'icon' => 'alerts_icon', 'url' => '', 'position' => 4],
            ['name' => 'Reports', 'icon' => 'reports_icon', 'url' => '', 'position' => 5],
            ['name' => 'Configuration', 'icon' => 'configuration_icon', 'url' => '', 'position' => 6],
            ['name' => 'Minehaul AI', 'icon' => 'ai_icon', 'url' => '', 'position' => 7],
            ['name' => 'Archive', 'icon' => 'archive_icon', 'url' => '', 'position' => 8],
            ['name' => 'System', 'icon' => 'system_icon', 'url' => '', 'position' => 9],
            ['name' => 'Account Onboarding', 'icon' => 'account_onboarding_icon', 'url' => '', 'position' => 10],
            ['name' => 'Manage All Accounts', 'icon' => 'manage_accounts_icon', 'url' => 'api/accounts/manage', 'position' => 11],
            ['name' => 'All Features Activation', 'icon' => 'features_activation_icon', 'url' => 'api/features/activate', 'position' => 12],
            ['name' => 'Account Billing Management', 'icon' => 'billing_management_icon', 'url' => 'api/accounts/billing', 'position' => 13],
        ];

        // Save inserted menu IDs
        $menuIds = [];
        foreach ($menus as $menu) {
            $menu['key'] = Str::slug($menu['name']);
            $menu['created_at'] = now();
            $menu['updated_at'] = now();
            $menuIds[$menu['name']] = DB::table('menus')->insertGetId($menu);
        }

        // Insert submenus for 'Configuration'
        $configurationSubmenus = [
            ['name' => 'Vehicle', 'icon' => 'vehicle_icon', 'url' => 'api/vehicles', 'position' => 1, 'parent_id' => $menuIds['Configuration']],
            ['name' => 'Device', 'icon' => 'device_icon', 'url' => 'api/devices', 'position' => 2, 'parent_id' => $menuIds['Configuration']],
            ['name' => 'Driver', 'icon' => 'driver_icon', 'url' => 'api/drivers', 'position' => 3, 'parent_id' => $menuIds['Configuration']],
        ];

        $configurationSubmenuIds = [];
        foreach ($configurationSubmenus as $submenu) {
            $submenu['key'] = Str::slug($submenu['name']);
            $submenu['created_at'] = now();
            $submenu['updated_at'] = now();
            $configurationSubmenuIds[] = DB::table('menus')->insertGetId($submenu);
        }

        // Insert submenus for 'System'
        $systemSubmenus = [
            ['name' => 'Permission', 'icon' => 'permission_icon', 'url' => 'api/permissions', 'position' => 1, 'parent_id' => $menuIds['System']],
            ['name' => 'Role', 'icon' => 'role_icon', 'url' => 'api/roles', 'position' => 2, 'parent_id' => $menuIds['System']],
            ['name' => 'User', 'icon' => 'user_icon', 'url' => 'api/users', 'position' => 3, 'parent_id' => $menuIds['System']],
            ['name' => 'Menu', 'icon' => 'menu_icon', 'url' => 'api/menus', 'position' => 4, 'parent_id' => $menuIds['System']],
        ];

        $systemSubmenuIds = [];
        foreach ($systemSubmenus as $submenu) {
            $submenu['key'] = Str::slug($submenu['name']);
            $submenu['created_at'] = now();
            $submenu['updated_at'] = now();
            $systemSubmenuIds[] = DB::table('menus')->insertGetId($submenu);
        }

        // Assign all menus to Administrator User role
        foreach ($menuIds as $menuId) {
            DB::table('role_menus')->insert([
                'role_id' => $administratorUser->id,
                'menu_id' => $menuId,
            ]);
        }

        // Assign all submenus to Administrator User role
        foreach (array_merge($configurationSubmenuIds, $systemSubmenuIds) as $submenuId) {
            DB::table('role_menus')->insert([
                'role_id' => $administratorUser->id,
                'menu_id' => $submenuId,
            ]);
        }

        // Assign selected menus to Super Account role
        $superAccountMenus = [
            'Account Onboarding',
            'Manage All Accounts',
            'All Features Activation',
            'Account Billing Management',
        ];

        foreach ($superAccountMenus as $menuName) {
            DB::table('role_menus')->insert([
                'role_id' => $superAccount->id,
                'menu_id' => $menuIds[$menuName],
            ]);
        }

        // Assign selected menus to Super User role
        $superUserMenus = [
            'Account Onboarding',
            'Manage All Accounts',
            'All Features Activation',
            'Account Billing Management',
        ];

        foreach ($superUserMenus as $menuName) {
            DB::table('role_menus')->insert([
                'role_id' => $superUser->id,
                'menu_id' => $menuIds[$menuName],
            ]);
        }

        // Assign all menus except 'Configuration' and 'System' to Regular User role
        foreach ($menuIds as $menuName => $menuId) {
            if (! in_array($menuName, ['Configuration', 'System'])) {
                DB::table('role_menus')->insert([
                    'role_id' => $regularUser->id,
                    'menu_id' => $menuId,
                ]);
            }
        }

        // Ensure submenus under 'Configuration' and 'System' are not assigned to Regular User role
        $excludedSubmenus = array_merge($configurationSubmenuIds, $systemSubmenuIds);

        foreach ($configurationSubmenuIds as $submenuId) {
            if (! in_array($submenuId, $excludedSubmenus)) {
                DB::table('role_menus')->insert([
                    'role_id' => $regularUser->id,
                    'menu_id' => $submenuId,
                ]);
            }
        }

        foreach ($systemSubmenuIds as $submenuId) {
            if (! in_array($submenuId, $excludedSubmenus)) {
                DB::table('role_menus')->insert([
                    'role_id' => $regularUser->id,
                    'menu_id' => $submenuId,
                ]);
            }
        }

        // Insert into permission_menus table for permissions
        foreach ($permissions as $permission) {
            // Assign menus to all permissions
            foreach ($menuIds as $menuId) {
                DB::table('permission_menus')->insert([
                    'permission_id' => $permission->id,
                    'menu_id' => $menuId,
                    'status' => 'read',
                ]);
            }

            // Assign submenus to all permissions
            foreach (array_merge($configurationSubmenuIds, $systemSubmenuIds) as $submenuId) {
                DB::table('permission_menus')->insert([
                    'permission_id' => $permission->id,
                    'menu_id' => $submenuId,
                    'status' => 'read',
                ]);
            }
        }
    }
}

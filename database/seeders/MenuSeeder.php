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

        // Assign menus to Super Accounts role
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

        // Assign menus to Super User role
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

        // Assign all menus to Administrator User role
        foreach ($menuIds as $menuId) {
            DB::table('role_menus')->insert([
                'role_id' => $administratorUser->id,
                'menu_id' => $menuId,
            ]);
        }
    }
}

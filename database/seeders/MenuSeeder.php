<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch roles
        $superAdminRole = Role::where('name', 'Super Administrator')->first();
        $accountRole = Role::where('name', 'Account')->first();

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
        ];

        // Simpan ID menu yang sudah diinsert untuk referensi parent_id
        $menuIds = [];
        foreach ($menus as $menu) {
            $menu['key'] = Str::slug($menu['name']);
            $menu['created_at'] = now();
            $menu['updated_at'] = now();
            $menuIds[$menu['name']] = DB::table('menus')->insertGetId($menu);
        }

        // Insert submenus for 'Configuration'
        $configurationSubmenus = [
            ['name' => 'Vehicle', 'icon' => 'vehicle_icon', 'url' => 'api/vehicle', 'position' => 1, 'parent_id' => $menuIds['Configuration']],
            ['name' => 'Device', 'icon' => 'device_icon', 'url' => 'api/device', 'position' => 2, 'parent_id' => $menuIds['Configuration']],
            ['name' => 'Driver', 'icon' => 'driver_icon', 'url' => 'api/driver', 'position' => 3, 'parent_id' => $menuIds['Configuration']],
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
            ['name' => 'Permission', 'icon' => 'permission_icon', 'url' => 'api/permission', 'position' => 1, 'parent_id' => $menuIds['System']],
            ['name' => 'Role', 'icon' => 'role_icon', 'url' => 'api/role', 'position' => 2, 'parent_id' => $menuIds['System']],
            ['name' => 'User', 'icon' => 'user_icon', 'url' => 'api/user', 'position' => 3, 'parent_id' => $menuIds['System']],
            ['name' => 'Menu', 'icon' => 'menu_icon', 'url' => 'api/menu', 'position' => 4, 'parent_id' => $menuIds['System']],
        ];

        $systemSubmenuIds = [];
        foreach ($systemSubmenus as $submenu) {
            $submenu['key'] = Str::slug($submenu['name']);
            $submenu['created_at'] = now();
            $submenu['updated_at'] = now();
            $systemSubmenuIds[] = DB::table('menus')->insertGetId($submenu);
        }

        // Assign all menus to super_admin role
        foreach ($menuIds as $menuId) {
            DB::table('role_menus')->insert([
                'role_id' => $superAdminRole->id,
                'menu_id' => $menuId,
            ]);
        }

        // Assign all submenus to super_admin role
        foreach (array_merge($configurationSubmenuIds, $systemSubmenuIds) as $submenuId) {
            DB::table('role_menus')->insert([
                'role_id' => $superAdminRole->id,
                'menu_id' => $submenuId,
            ]);
        }

        // Assign menus to account role except 'Configuration' and 'System' menus and their submenus
        foreach ($menuIds as $menuName => $menuId) {
            if (! in_array($menuName, ['Configuration', 'System'])) {
                DB::table('role_menus')->insert([
                    'role_id' => $accountRole->id,
                    'menu_id' => $menuId,
                ]);
            }
        }

        // Ensure submenus under 'Configuration' and 'System' are not assigned to account role
        $excludedSubmenus = array_merge($configurationSubmenuIds, $systemSubmenuIds);

        foreach ($configurationSubmenuIds as $submenuId) {
            if (! in_array($submenuId, $excludedSubmenus)) {
                DB::table('role_menus')->insert([
                    'role_id' => $accountRole->id,
                    'menu_id' => $submenuId,
                ]);
            }
        }

        foreach ($systemSubmenuIds as $submenuId) {
            if (! in_array($submenuId, $excludedSubmenus)) {
                DB::table('role_menus')->insert([
                    'role_id' => $accountRole->id,
                    'menu_id' => $submenuId,
                ]);
            }
        }
    }
}

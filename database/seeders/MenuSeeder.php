<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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

        foreach ($configurationSubmenus as $submenu) {
            $submenu['key'] = Str::slug($submenu['name']);
            $submenu['created_at'] = now();
            $submenu['updated_at'] = now();
            DB::table('menus')->insert($submenu);
        }

        // Insert submenus for 'System'
        $systemSubmenus = [
            ['name' => 'Permission', 'icon' => 'permission_icon', 'url' => 'api/permission', 'position' => 1, 'parent_id' => $menuIds['System']],
            ['name' => 'Role', 'icon' => 'role_icon', 'url' => 'api/role', 'position' => 2, 'parent_id' => $menuIds['System']],
            ['name' => 'User', 'icon' => 'user_icon', 'url' => 'api/user', 'position' => 3, 'parent_id' => $menuIds['System']],
            ['name' => 'Menu', 'icon' => 'menu_icon', 'url' => 'api/menu', 'position' => 4, 'parent_id' => $menuIds['System']],
        ];

        foreach ($systemSubmenus as $submenu) {
            $submenu['key'] = Str::slug($submenu['name']);
            $submenu['created_at'] = now();
            $submenu['updated_at'] = now();
            DB::table('menus')->insert($submenu);
        }
    }
}

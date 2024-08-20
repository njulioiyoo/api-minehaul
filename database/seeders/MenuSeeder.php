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
        $menus = [
            ['name' => 'Dashboard', 'icon' => 'dashboard_icon', 'url' => '', 'position' => 1],
            ['name' => 'Tracking', 'icon' => 'tracking_icon', 'url' => '', 'position' => 2],
            ['name' => 'Navigation', 'icon' => 'navigation_icon', 'url' => '', 'position' => 3],
            ['name' => 'Alerts', 'icon' => 'alerts_icon', 'url' => '', 'position' => 4],
            ['name' => 'Reports', 'icon' => 'reports_icon', 'url' => '', 'position' => 5],
            ['name' => 'Reports history', 'icon' => 'history_icon', 'url' => '', 'position' => 6],
            ['name' => 'Configuration', 'icon' => 'configuration_icon', 'url' => '', 'position' => 7],
        ];

        // Insert main menus
        foreach ($menus as $menu) {
            $menu['key'] = Str::slug($menu['name']);
            $menu['created_at'] = now();
            $menu['updated_at'] = now();
            $menu['id'] = DB::table('menus')->insertGetId($menu);
        }

        // Insert submenus for 'configuration'
        $submenus = [
            ['name' => 'Permission', 'icon' => 'permission_icon', 'url' => 'api/permission', 'position' => 1],
            ['name' => 'Role', 'icon' => 'role_icon', 'url' => 'api/role', 'position' => 2],
            ['name' => 'User', 'icon' => 'user_icon', 'url' => 'api/user', 'position' => 3],
            ['name' => 'Menu', 'icon' => 'menu_icon', 'url' => 'api/menu', 'position' => 4],
        ];

        // Insert submenus
        foreach ($submenus as $submenu) {
            $submenu['key'] = Str::slug($submenu['name']);
            $submenu['created_at'] = now();
            $submenu['updated_at'] = now();
            DB::table('menus')->insert($submenu);
        }

        // Insert other menus
        $otherMenus = [
            ['name' => 'Tags', 'icon' => 'tags_icon', 'url' => '', 'position' => 8],
            ['name' => 'Notifications', 'icon' => 'notifications_icon', 'url' => '', 'position' => 9],
            ['name' => 'Minehaul AI', 'icon' => 'ai_icon', 'url' => '', 'position' => 10],
        ];

        // Insert remaining menus
        foreach ($otherMenus as $menu) {
            $menu['key'] = Str::slug($menu['name']);
            $menu['created_at'] = now();
            $menu['updated_at'] = now();
            DB::table('menus')->insert($menu);
        }
    }
}

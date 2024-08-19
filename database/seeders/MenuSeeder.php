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
            ['name' => 'dashboard', 'icon' => 'dashboard_icon', 'url' => '/dashboard', 'position' => 1],
            ['name' => 'tracking', 'icon' => 'tracking_icon', 'url' => '/tracking', 'position' => 2],
            ['name' => 'navigation', 'icon' => 'navigation_icon', 'url' => '/navigation', 'position' => 3],
            ['name' => 'alerts', 'icon' => 'alerts_icon', 'url' => '/alerts', 'position' => 4],
            ['name' => 'reports', 'icon' => 'reports_icon', 'url' => '/reports', 'position' => 5],
            ['name' => 'reports history', 'icon' => 'history_icon', 'url' => '/reports/history', 'position' => 6],
            ['name' => 'configuration', 'icon' => 'configuration_icon', 'url' => '/configuration', 'position' => 7],
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
            ['name' => 'permission', 'icon' => 'permission_icon', 'url' => '/configuration/permission', 'position' => 1],
            ['name' => 'role', 'icon' => 'role_icon', 'url' => '/configuration/role', 'position' => 2],
            ['name' => 'user', 'icon' => 'user_icon', 'url' => '/configuration/user', 'position' => 3],
            ['name' => 'menu', 'icon' => 'menu_icon', 'url' => '/configuration/menu', 'position' => 4],
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
            ['name' => 'tags', 'icon' => 'tags_icon', 'url' => '/tags', 'position' => 8],
            ['name' => 'notifications', 'icon' => 'notifications_icon', 'url' => '/notifications', 'position' => 9],
            ['name' => 'minehaul ai', 'icon' => 'ai_icon', 'url' => '/minehaul-ai', 'position' => 10],
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

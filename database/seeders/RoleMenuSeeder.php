<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Contoh penugasan menu ke pengguna. Sesuaikan dengan ID pengguna dan menu yang tersedia di database Anda.

        // Asumsikan kita memiliki beberapa role dengan ID 1, 2, dan 3
        // Dan kita akan memberikan mereka menu yang berbeda.

        // Role 1 mendapatkan akses ke menu Dashboard, Tracking, dan Alerts
        DB::table('role_menus')->insert([
            ['role_id' => 1, 'menu_id' => 1], // Dashboard
            ['role_id' => 1, 'menu_id' => 2], // Tracking
            ['role_id' => 1, 'menu_id' => 4], // Alerts
        ]);

        // Role 2 mendapatkan akses ke menu Dashboard, Reports, dan Configuration
        DB::table('role_menus')->insert([
            ['role_id' => 2, 'menu_id' => 1], // Dashboard
            ['role_id' => 2, 'menu_id' => 5], // Reports
            ['role_id' => 2, 'menu_id' => 7], // Configuration
        ]);

        // Role 3 mendapatkan akses ke semua menu
        $menus = DB::table('menus')->get();
        foreach ($menus as $menu) {
            DB::table('role_menus')->insert([
                'role_id' => 2,
                'menu_id' => $menu->id,
            ]);
        }
    }
}

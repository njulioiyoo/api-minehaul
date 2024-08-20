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
        $roles = DB::table('roles')->pluck('id')->toArray();
        $menus = DB::table('menus')->pluck('id')->toArray();

        if (! in_array(1, $roles)) {
            throw new \Exception('Role ID 1 not found in roles table');
        }

        if (! in_array(2, $roles)) {
            throw new \Exception('Role ID 2 not found in roles table');
        }

        DB::table('role_menus')->insert([
            ['role_id' => 1, 'menu_id' => 1],
            ['role_id' => 1, 'menu_id' => 2],
            ['role_id' => 1, 'menu_id' => 4],
        ]);

        DB::table('role_menus')->insert([
            ['role_id' => 2, 'menu_id' => 1],
            ['role_id' => 2, 'menu_id' => 5],
            ['role_id' => 2, 'menu_id' => 7],
        ]);

        $menus = DB::table('menus')->get();
        foreach ($menus as $menu) {
            DB::table('role_menus')->insert([
                'role_id' => 3,
                'menu_id' => $menu->id,
            ]);
        }
    }
}

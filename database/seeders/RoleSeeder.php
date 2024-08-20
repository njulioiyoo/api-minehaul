<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Super Administrator', 'guard_name' => 'api'],
            ['name' => 'Accountan', 'guard_name' => 'api'],
            ['name' => 'Admin', 'guard_name' => 'api'],
            ['name' => 'Manajer Tambang', 'guard_name' => 'api'],
            ['name' => 'Operator Peralatan', 'guard_name' => 'api'],
            ['name' => 'Pengawas Keselamatan', 'guard_name' => 'api'],
            ['name' => 'Analis Produksi', 'guard_name' => 'api'],
            ['name' => 'Pengelola Lingkungan', 'guard_name' => 'api'],
            ['name' => 'Keuangan', 'guard_name' => 'api'],
            ['name' => 'Personel Tambang', 'guard_name' => 'api'],
            ['name' => 'Staf Administrasi', 'guard_name' => 'api'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->insert(array_merge($role, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}

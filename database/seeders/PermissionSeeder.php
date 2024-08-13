<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'View Mine Data', 'guard_name' => 'api'],
            ['name' => 'Edit Mine Data', 'guard_name' => 'api'],
            ['name' => 'Delete Mine Data', 'guard_name' => 'api'],
            ['name' => 'Manage Equipment', 'guard_name' => 'api'],
            ['name' => 'View Equipment Status', 'guard_name' => 'api'],
            ['name' => 'Edit Equipment Status', 'guard_name' => 'api'],
            ['name' => 'Manage Personnel', 'guard_name' => 'api'],
            ['name' => 'View Personnel Records', 'guard_name' => 'api'],
            ['name' => 'Edit Personnel Records', 'guard_name' => 'api'],
            ['name' => 'View Production Reports', 'guard_name' => 'api'],
            ['name' => 'Generate Production Reports', 'guard_name' => 'api'],
            ['name' => 'Manage Safety Protocols', 'guard_name' => 'api'],
            ['name' => 'View Safety Incidents', 'guard_name' => 'api'],
            ['name' => 'Report Safety Incidents', 'guard_name' => 'api'],
            ['name' => 'Manage Environmental Compliance', 'guard_name' => 'api'],
            ['name' => 'View Environmental Reports', 'guard_name' => 'api'],
            ['name' => 'Edit Environmental Reports', 'guard_name' => 'api'],
            ['name' => 'Manage Financial Records', 'guard_name' => 'api'],
            ['name' => 'View Financial Reports', 'guard_name' => 'api'],
            ['name' => 'Approve Financial Transactions', 'guard_name' => 'api'],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->insert(array_merge($permission, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}

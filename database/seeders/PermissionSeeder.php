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
            // Permissions for Account
            ['name' => 'View Dashboard', 'guard_name' => 'api'],
            ['name' => 'View Tracking', 'guard_name' => 'api'],
            ['name' => 'View Navigation', 'guard_name' => 'api'],
            ['name' => 'Create Navigation', 'guard_name' => 'api'],
            ['name' => 'Edit Navigation', 'guard_name' => 'api'],
            ['name' => 'Delete Navigation', 'guard_name' => 'api'],
            ['name' => 'View Alerts', 'guard_name' => 'api'],
            ['name' => 'View Reports', 'guard_name' => 'api'],
            ['name' => 'View Reports History', 'guard_name' => 'api'],
            //Start - Configuration
            ['name' => 'View Vehicle', 'guard_name' => 'api'],
            ['name' => 'Create Vehicle', 'guard_name' => 'api'],
            ['name' => 'Edit Vehicle', 'guard_name' => 'api'],
            ['name' => 'Delete Vehicle', 'guard_name' => 'api'],
            ['name' => 'View Device', 'guard_name' => 'api'],
            ['name' => 'Create Device', 'guard_name' => 'api'],
            ['name' => 'Edit Device', 'guard_name' => 'api'],
            ['name' => 'Delete Device', 'guard_name' => 'api'],
            ['name' => 'View Driver', 'guard_name' => 'api'],
            ['name' => 'Create Driver', 'guard_name' => 'api'],
            ['name' => 'Edit Driver', 'guard_name' => 'api'],
            ['name' => 'Delete Driver', 'guard_name' => 'api'],
            //End - Configuration
            ['name' => 'View Tags', 'guard_name' => 'api'],
            ['name' => 'View Notifications', 'guard_name' => 'api'],
            ['name' => 'View Minehaul AI', 'guard_name' => 'api'],

            // Permissions for Super Administrator
            //Start - System
            ['name' => 'View Users', 'guard_name' => 'api'],
            ['name' => 'Create Users', 'guard_name' => 'api'],
            ['name' => 'Edit Users', 'guard_name' => 'api'],
            ['name' => 'Delete Users', 'guard_name' => 'api'],
            ['name' => 'View Roles', 'guard_name' => 'api'],
            ['name' => 'Create Roles', 'guard_name' => 'api'],
            ['name' => 'Edit Roles', 'guard_name' => 'api'],
            ['name' => 'Delete Roles', 'guard_name' => 'api'],
            ['name' => 'View Menus', 'guard_name' => 'api'],
            ['name' => 'Edit Menus', 'guard_name' => 'api'],
            //End - System
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->insert(array_merge($permission, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}

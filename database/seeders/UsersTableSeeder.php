<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'username' => 'administrator_user',
                'people_id' => '1',
                'email' => 'administrator_user@admin.com',
                'password' => Hash::make('password'),
                'uid' => Str::uuid(),
            ],
            [
                'username' => 'regular_user',
                'people_id' => '2',
                'email' => 'regular_user@company.com',
                'password' => Hash::make('password'),
                'uid' => Str::uuid(),
            ],
            [
                'username' => 'super_account',
                'people_id' => '1',
                'email' => 'super_account@company.com',
                'password' => Hash::make('password'),
                'uid' => Str::uuid(),
            ],
            [
                'username' => 'super_user',
                'people_id' => '2',
                'email' => 'super_user@company.com',
                'password' => Hash::make('password'),
                'uid' => Str::uuid(),
            ],
        ];

        DB::table('users')->insert($users);
    }
}

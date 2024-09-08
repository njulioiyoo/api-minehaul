<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
                'person_id' => '1',
                'email' => 'administrator_user@admin.com',
                'password' => Hash::make('password'),
            ],
            [
                'username' => 'regular_user',
                'person_id' => '2',
                'email' => 'regular_user@company.com',
                'password' => Hash::make('password'),
            ],
            [
                'username' => 'super_account',
                'person_id' => '1',
                'email' => 'super_account@company.com',
                'password' => Hash::make('password'),
            ],
            [
                'username' => 'super_user',
                'person_id' => '2',
                'email' => 'super_user@company.com',
                'password' => Hash::make('password'),
            ],
        ];

        DB::table('users')->insert($users);
    }
}

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
                'username' => 'super_administrator',
                'person_id' => '1',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'),
            ],
            [
                'username' => 'accountan',
                'person_id' => '2',
                'email' => 'accountan@company.com',
                'password' => Hash::make('accountan_password'),
            ],
        ];

        DB::table('users')->insert($users);
    }
}

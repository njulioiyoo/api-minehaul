<?php

declare(strict_types=1);

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $pitUids = DB::table('pits')->pluck('uid')->toArray();
        $accountUids = DB::table('accounts')->pluck('uid')->toArray();

        for ($i = 0; $i < 30; $i++) {
            DB::table('drivers')->insert([
                'account_id' => $accountUids[array_rand($accountUids)],
                'pit_id' => $pitUids[array_rand($pitUids)],
                'display_id' => $faker->regexify('[A-Za-z0-9]{10}'),
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'phone_number' => $faker->phoneNumber(),
                'created_by' => $faker->numberBetween(1, 10),
                'updated_by' => $faker->numberBetween(1, 10),
                'uid' => Str::uuid(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

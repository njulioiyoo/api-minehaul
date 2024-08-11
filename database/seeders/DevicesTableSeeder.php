<?php

declare(strict_types=1);

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DevicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $statuses = ['active', 'inactive', 'nullified'];

        for ($i = 0; $i < 100; $i++) {
            DB::table('devices')->insert([
                'account_id' => $faker->optional()->randomNumber(),
                'device_type_id' => $faker->optional()->randomNumber(),
                'device_display_id' => $faker->optional()->bothify('??-#####'),
                'device_name' => $faker->optional()->word,
                'device_sim_id' => $faker->optional()->bothify('SIM-########'),
                'device_year' => $faker->optional()->year,
                'device_make_id' => $faker->optional()->randomNumber(),
                'device_model_id' => $faker->optional()->randomNumber(),
                'device_status_id' => $faker->optional()->randomNumber(),
                'dt_status' => $faker->randomElement($statuses),
                'dt_creator' => $faker->optional()->randomNumber(),
                'dt_create_date' => $faker->optional()->dateTime,
                'dt_editor' => $faker->optional()->randomNumber(),
                'dt_edit_date' => $faker->optional()->dateTime,
                'uid' => $faker->optional()->uuid,
            ]);
        }
    }
}

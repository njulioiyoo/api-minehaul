<?php

declare(strict_types=1);

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 10; $i++) {
            DB::table('vehicles')->insert([
                'account_id' => rand(1, 10),
                'pit_id' => rand(1, 10),
                'display_id' => 'V'.str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                'name' => $faker->company.' '.$faker->word,
                'vin' => strtoupper($faker->bothify(strtoupper('??######??######?#'))),
                'license_plate' => strtoupper($faker->bothify('?? #### ??')),
                'vehicle_type_id' => $faker->numberBetween(1, 2),
                'vehicle_make_id' => $faker->numberBetween(1, 9),
                'vehicle_model_id' => $faker->numberBetween(1, 54),
                'year' => $faker->year(),
                'vehicle_status_id' => $faker->numberBetween(1, 3),
                'created_by' => $faker->numberBetween(1, 2),
                'updated_by' => $faker->numberBetween(1, 2),
                'uid' => Str::uuid(),
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ]);
        }
    }
}

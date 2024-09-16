<?php

declare(strict_types=1);

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) {
            DB::table('locations')->insert([
                'account_id' => $faker->numberBetween(1, 10),
                'pit_id' => $faker->numberBetween(1, 10),
                'location_type_id' => $faker->numberBetween(1, 5),
                'name' => $faker->streetName,
                'geom_type' => $faker->randomElement(['Polygon', 'Point']),
                'geom' => $faker->randomElement([
                    'POLYGON((30 10, 40 40, 20 40, 10 20, 30 10))',
                    'POINT(30 10)',
                ]),
                'radius' => $faker->randomFloat(2, 1, 100),
                'created_by' => $faker->numberBetween(1, 10),
                'updated_by' => $faker->numberBetween(1, 10),
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ]);
        }
    }
}

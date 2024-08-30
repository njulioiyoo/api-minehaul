<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $vehicleTypes = [
            'Dump Truck',
            'Excavator'
        ];

        foreach ($vehicleTypes as $vehicleType) {
            DB::table('vehicle_types')->insert([
                'name' => $vehicleType,
                'created_by' => $faker->randomDigitNotNull,
                'updated_by' => $faker->randomDigitNotNull,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ]);
        }
    }
}

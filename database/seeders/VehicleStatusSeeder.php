<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class VehicleStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $vehicleStatuss = [
            'Normal',
            'Repair',
            'Breakdown',
        ];

        foreach ($vehicleStatuss as $vehicleStatus) {
            DB::table('vehicle_statuses')->insert([
                'name' => $vehicleStatus,
                'created_by' => $faker->randomDigitNotNull,
                'updated_by' => $faker->randomDigitNotNull,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ]);
        }
    }
}

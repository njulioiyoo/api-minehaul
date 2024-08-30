<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class VehicleMakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $vehicleTypes = DB::table('vehicle_types')->pluck('id', 'name');

        $vehicleMakes = [
            'Dump Truck' => ['Caterpillar', 'Komatsu', 'Hitachi', 'Volvo'],
            'Excavator' => ['Caterpillar', 'Komatsu', 'Hitachi', 'Volvo', 'Kubota'],
        ];

        foreach ($vehicleTypes as $typeName => $typeId) {
            if (isset($vehicleMakes[$typeName])) {
                foreach ($vehicleMakes[$typeName] as $makeName) {
                    DB::table('vehicle_makes')->insert([
                        'vehicle_type_id' => $typeId,
                        'name' => $makeName,
                        'created_by' => $faker->randomDigitNotNull,
                        'updated_by' => $faker->randomDigitNotNull,
                        'created_at' => now(),
                        'updated_at' => now(),
                        'deleted_at' => null,
                    ]);
                }
            }
        }
    }
}

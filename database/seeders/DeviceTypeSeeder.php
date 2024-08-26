<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DeviceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $deviceTypes = [
            'Smartphone',
            'Tablet',
            'Laptop',
            'Desktop',
            'Wearable',
            'IoT Device',
            'Printer',
            'Router',
            'Server'
        ];

        foreach ($deviceTypes as $deviceType) {
            DB::table('device_types')->insert([
                'name' => $deviceType,
                'created_by' => $faker->randomDigitNotNull,
                'updated_by' => $faker->randomDigitNotNull,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ]);
        }
    }
}

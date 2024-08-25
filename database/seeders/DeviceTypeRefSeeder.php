<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DeviceTypeRefSeeder extends Seeder
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
            DB::table('device_type_ref')->insert([
                'name' => $deviceType,
                'status' => $faker->randomElement(['active', 'inactive', 'nullified']),
                'created_by' => $faker->randomDigitNotNull,
                'updated_by' => $faker->randomDigitNotNull,
                'uid' => Str::uuid()->toString(),
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ]);
        }
    }
}

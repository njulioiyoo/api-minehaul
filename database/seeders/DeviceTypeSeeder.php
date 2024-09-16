<?php

declare(strict_types=1);

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            'GPS',
            'BLE Beacon',
            'Load Scanner',
            'Smartphone',
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

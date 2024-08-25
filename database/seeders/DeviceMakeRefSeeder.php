<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DeviceMakeRefSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $deviceMakes = [
            'Apple',
            'Samsung',
            'Dell',
            'HP',
            'Lenovo',
            'Asus',
            'Acer',
            'Microsoft',
            'Google',
            'Huawei'
        ];

        foreach ($deviceMakes as $deviceMake) {
            DB::table('device_make_ref')->insert([
                'name' => $deviceMake,
                'status' => $faker->randomElement(['active', 'inactive', 'nullified']),
                'created_by' => $faker->randomDigitNotNull,
                'updated_by' => $faker->randomDigitNotNull,
                'uid' => Str::uuid()->toString(),
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ]);
        }
    }
}

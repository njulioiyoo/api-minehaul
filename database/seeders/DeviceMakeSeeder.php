<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DeviceMakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $deviceTypes = DB::table('device_types')->pluck('id', 'name');

        $deviceMakes = [
            'Smartphone' => ['Apple', 'Samsung', 'Dell', 'HP', 'Lenovo', 'Asus', 'Acer', 'Microsoft', 'Google', 'Huawei'],
            'Tablet' => ['Apple', 'Samsung', 'Dell', 'HP', 'Lenovo', 'Asus', 'Acer', 'Microsoft', 'Google', 'Huawei'],
            'Laptop' => ['Apple', 'Samsung', 'Dell', 'HP', 'Lenovo', 'Asus', 'Acer', 'Microsoft', 'Google', 'Huawei'],
            'Desktop' => ['Apple', 'Samsung', 'Dell', 'HP', 'Lenovo', 'Asus', 'Acer', 'Microsoft', 'Google', 'Huawei'],
            'Wearable' => ['Apple', 'Samsung', 'Dell', 'HP', 'Lenovo', 'Asus', 'Acer', 'Microsoft', 'Google', 'Huawei'],
            'IoT Device' => ['Apple', 'Samsung', 'Dell', 'HP', 'Lenovo', 'Asus', 'Acer', 'Microsoft', 'Google', 'Huawei'],
            'Printer' => ['Apple', 'Samsung', 'Dell', 'HP', 'Lenovo', 'Asus', 'Acer', 'Microsoft', 'Google', 'Huawei'],
            'Router' => ['Apple', 'Samsung', 'Dell', 'HP', 'Lenovo', 'Asus', 'Acer', 'Microsoft', 'Google', 'Huawei'],
            'Server' => ['Apple', 'Samsung', 'Dell', 'HP', 'Lenovo', 'Asus', 'Acer', 'Microsoft', 'Google', 'Huawei'],
        ];

        foreach ($deviceTypes as $typeName => $typeId) {
            if (isset($deviceMakes[$typeName])) {
                foreach ($deviceMakes[$typeName] as $makeName) {
                    DB::table('device_makes')->insert([
                        'device_type_id' => $typeId,
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

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DeviceModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $deviceMakes = DB::table('device_makes')->pluck('id', 'name');

        $deviceModels = [
            'Apple' => ['iPhone 12', 'iPhone 13', 'iPad Pro', 'MacBook Air'],
            'Samsung' => ['Galaxy S21', 'Galaxy Note 20', 'Galaxy Tab S7'],
            'Dell' => ['XPS 13', 'Inspiron 15', 'Alienware M15'],
            'HP' => ['Pavilion 15', 'Envy 13', 'Spectre x360'],
            'Lenovo' => ['ThinkPad X1', 'Yoga 7i', 'Legion 5'],
            'Asus' => ['ZenBook 14', 'ROG Strix G15', 'VivoBook 15'],
            'Acer' => ['Aspire 5', 'Predator Helios 300', 'Swift 3'],
            'Microsoft' => ['Surface Pro 7', 'Surface Laptop 4'],
            'Google' => ['Pixel 5', 'Pixelbook Go'],
            'Huawei' => ['MateBook X Pro', 'P40 Pro']
        ];

        foreach ($deviceMakes as $makeName => $makeId) {
            if (isset($deviceModels[$makeName])) {
                foreach ($deviceModels[$makeName] as $modelName) {
                    DB::table('device_models')->insert([
                        'device_make_id' => $makeId,
                        'name' => $modelName,
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

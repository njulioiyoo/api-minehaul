<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeviceIgnitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['id' => 0, 'name' => 'Normal'],
            ['id' => 1, 'name' => 'Spark Ignition (SI)'],
            ['id' => 2, 'name' => 'Compression Ignition (CI)'],
            ['id' => 3, 'name' => 'Piezoelectric Ignition'],
            ['id' => 4, 'name' => 'Hot Surface Ignition'],
            ['id' => 5, 'name' => 'Electronic Ignition System'],
            ['id' => 6, 'name' => 'Manual Ignition'],
            ['id' => 7, 'name' => 'Pilot Light Ignition'],
            ['id' => 8, 'name' => 'Glow Plug Ignition'],
            ['id' => 9, 'name' => 'Flint Ignition'],
        ];

        DB::table('device_ignitions')->insert($data);
    }
}

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
            ['name' => 'Normal'],
            ['name' => 'Spark Ignition (SI)'],
            ['name' => 'Compression Ignition (CI)'],
            ['name' => 'Piezoelectric Ignition'],
            ['name' => 'Hot Surface Ignition'],
            ['name' => 'Electronic Ignition System'],
            ['name' => 'Manual Ignition'],
            ['name' => 'Pilot Light Ignition'],
            ['name' => 'Glow Plug Ignition'],
            ['name' => 'Flint Ignition'],
        ];

        DB::table('device_ignition_types')->insert($data);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeviceImmobilizitationSeeder extends Seeder
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
            ['name' => 'Casts'],
            ['name' => 'Splints'],
            ['name' => 'Braces'],
            ['name' => 'Slings'],
            ['name' => 'Immobilization Boots'],
            ['name' => 'External Fixators'],
            ['name' => 'Traction Devices']
        ];

        DB::table('device_immobilizitation_types')->insert($data);
    }
}

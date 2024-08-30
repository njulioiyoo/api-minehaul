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
            ['id' => 0, 'name' => 'Normal'],
            ['id' => 1, 'name' => 'Casts'],
            ['id' => 2, 'name' => 'Splints'],
            ['id' => 3, 'name' => 'Braces'],
            ['id' => 4, 'name' => 'Slings'],
            ['id' => 5, 'name' => 'Immobilization Boots'],
            ['id' => 6, 'name' => 'External Fixators'],
            ['id' => 7, 'name' => 'Traction Devices']
        ];

        DB::table('device_immobilizitation_types')->insert($data);
    }
}

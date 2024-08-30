<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeviceStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Availble'],
            ['name' => 'Assigned'],
            ['name' => 'Rejected']
        ];

        DB::table('device_statuses')->insert($data);
    }
}

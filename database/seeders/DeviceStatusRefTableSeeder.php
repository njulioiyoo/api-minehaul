<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeviceStatusRefTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('device_status_ref')->insert([
            [
                'device_status_id' => 1,
                'device_status_name' => 'Assigned',
                'device_status_theme' => 'primary',
                'dt_status' => 'active',
                'dt_creator' => null,
                'dt_create_date' => null,
                'dt_editor' => null,
                'dt_edit_date' => null,
                'uid' => null,
            ],
            [
                'device_status_id' => 2,
                'device_status_name' => 'Rejected',
                'device_status_theme' => 'danger',
                'dt_status' => 'active',
                'dt_creator' => null,
                'dt_create_date' => null,
                'dt_editor' => null,
                'dt_edit_date' => null,
                'uid' => null,
            ],
            [
                'device_status_id' => 3,
                'device_status_name' => 'Broken',
                'device_status_theme' => null,
                'dt_status' => 'nullified',
                'dt_creator' => null,
                'dt_create_date' => null,
                'dt_editor' => null,
                'dt_edit_date' => null,
                'uid' => null,
            ],
        ]);
    }
}

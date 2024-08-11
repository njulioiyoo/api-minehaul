<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DevicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('devices')->insert([
            'device_id' => 1,
            'account_id' => null,
            'device_type_id' => 0,
            'device_display_id' => 'DEV-001',
            'device_name' => 'Device Testing',
            'device_sim_id' => '001',
            'device_year' => 2024,
            'device_make_id' => 1,
            'device_model_id' => 1,
            'device_status_id' => 2,
            'dt_status' => 'active',
            'dt_creator' => 1,
            'dt_create_date' => '2024-07-15 20:33:15',
            'dt_editor' => 1,
            'dt_edit_date' => '2024-07-16 20:31:14',
            'uid' => 'b7db5ddaf4d705d6950074f0e4ccb19c',
        ]);
    }
}

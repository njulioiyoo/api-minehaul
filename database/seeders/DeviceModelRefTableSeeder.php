<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeviceModelRefTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('device_model_ref')->insert([
            'device_model_id' => 1,
            'device_make_id' => 1,
            'device_model_name' => '785',
            'dt_status' => 'active',
            'dt_creator' => null,
            'dt_create_date' => null,
            'dt_editor' => null,
            'dt_edit_date' => null,
            'uid' => null,
        ]);
    }
}

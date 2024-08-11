<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeviceTypeRefTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('device_type_ref')->insert([
            'device_type_id' => 1,
            'device_type_name' => 'Teltonica',
            'dt_status' => 'active',
            'dt_creator' => null,
            'dt_create_date' => null,
            'dt_editor' => null,
            'dt_edit_date' => null,
            'uid' => null,
        ]);
    }
}

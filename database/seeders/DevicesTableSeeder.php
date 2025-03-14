<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DevicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $devices = [];

        for ($i = 1; $i <= 10; $i++) {
            $display_id = ($i <= 3) ? 'LS102' : 'LS'.str_pad((string) rand(103, 999), 3, '0', STR_PAD_LEFT);

            $devices[] = [
                'account_id' => 1,
                'pit_id' => 1,
                'device_type_id' => rand(1, 4),
                'device_make_id' => rand(1, 5),
                'device_model_id' => rand(1, 5),
                'year' => rand(2018, 2024),
                'display_id' => $display_id,
                'name' => 'Device '.$i,
                'sim_id' => 'SIM'.str_pad((string) $i, 10, '0', STR_PAD_LEFT),
                'device_immobilizitation_type_id' => rand(1, 5),
                'device_ignition_type_id' => rand(1, 5),
                'vehicle_id' => rand(1, 10),
                'device_status_id' => rand(1, 2),
                'created_by' => rand(1, 5),
                'updated_by' => rand(1, 5),
                'uid' => Str::uuid()->toString(),
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ];
        }

        DB::table('devices')->insert($devices);
    }
}

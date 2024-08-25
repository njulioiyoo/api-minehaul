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
            $devices[] = [
                'account_id' => rand(1, 5),
                'pit_id' => rand(1, 5),
                'device_type_id' => rand(1, 5),
                'display_make_id' => rand(1, 5),
                'display_model_id' => rand(1, 5),
                'display_id' => 'D' . str_pad((string) $i, 5, '0', STR_PAD_LEFT),
                'name' => 'Device ' . $i,
                'sim_id' => 'SIM' . str_pad((string) $i, 10, '0', STR_PAD_LEFT),
                'year' => rand(2018, 2024),
                'status_id' => rand(1, 5),
                'status' => ['active', 'inactive', 'nullified'][rand(0, 2)],
                'created_by' => rand(1, 5),
                'updated_by' => rand(1, 5),
                'uid' => Str::uuid()->toString(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('devices')->insert($devices);
    }
}

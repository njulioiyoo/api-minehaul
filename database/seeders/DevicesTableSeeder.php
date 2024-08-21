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
                'account_id' => rand(1, 5),  // Ganti dengan ID akun yang valid
                'pit_id' => rand(1, 5),      // Ganti dengan ID pit yang valid
                'type_id' => rand(1, 5),     // Ganti dengan ID tipe yang valid
                'display_id' => 'D'.str_pad((string) $i, 5, '0', STR_PAD_LEFT),
                'name' => 'Device '.$i,
                'sim_id' => 'SIM'.str_pad((string) $i, 10, '0', STR_PAD_LEFT),
                'year' => rand(2018, 2024),
                'make_id' => rand(1, 5),     // Ganti dengan ID make yang valid
                'model_id' => rand(1, 5),    // Ganti dengan ID model yang valid
                'status_id' => rand(1, 5),   // Ganti dengan ID status yang valid
                'status' => ['active', 'inactive', 'nullified'][rand(0, 2)],
                'created_by' => rand(1, 5),  // Ganti dengan ID pengguna yang valid
                'updated_by' => rand(1, 5),  // Ganti dengan ID pengguna yang valid
                'uid' => Str::uuid()->toString(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('devices')->insert($devices);
    }
}

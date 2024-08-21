<?php

declare(strict_types=1);

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pits = [
            [
                'account_id' => 1,
                'name' => 'North Pit',
                'description' => 'Primary extraction pit located in the northern section.',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'account_id' => 2,
                'name' => 'South Pit',
                'description' => 'Smaller pit in the southern region.',
                'status' => 'inactive',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'account_id' => 3,
                'name' => 'East Pit',
                'description' => 'Pit under exploration in the eastern zone.',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'account_id' => 4,
                'name' => 'West Pit',
                'description' => 'Abandoned pit in the western area.',
                'status' => 'nullified',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('pits')->insert($pits);
    }
}

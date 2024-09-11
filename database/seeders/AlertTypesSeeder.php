<?php

declare(strict_types=1);

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlertTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alert_types')->insert([
            ['name' => 'Service Soon', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Speeding', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Low Battery', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Loading Alert', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Crash', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}

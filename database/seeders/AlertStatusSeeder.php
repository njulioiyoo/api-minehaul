<?php

declare(strict_types=1);

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlertStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alert_statuses')->insert([
            ['name' => 'Info', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Critical', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Emergency', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Warning', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}

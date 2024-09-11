<?php

declare(strict_types=1);

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlertActionTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alert_action_types')->insert([
            ['name' => 'checking', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'resolved', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}

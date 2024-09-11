<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitOfMeasurementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('unit_of_measurements')->insert([
            ['name' => 'minutes'],
            ['name' => 'km/hour'],
            ['name' => 'miles/hour'],
        ]);
    }
}

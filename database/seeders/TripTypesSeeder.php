<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TripTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menambahkan beberapa data trip_types
        DB::table('trip_types')->insert([
            ['name' => 'dumping'],
            ['name' => 'hauling'],
        ]);
    }
}

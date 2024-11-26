<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TripTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tripTypes = [
            ['name' => 'dumping'],
            ['name' => 'hauling'],
        ];

        DB::table('trip_types')->insert($tripTypes);
    }
}

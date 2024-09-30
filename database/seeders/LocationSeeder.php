<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('locations')->insert([
            [
                'account_id' => 1,
                'pit_id' => 1,
                'location_type_id' => 3,
                'name' => 'PIT Area A',
                'geom_type' => 'Point',
                'geom' => 'POINT(115.316510780521995 -3.7196283683299)',
                'radius' => 50,
                'uid' => Str::uuid(),
                'created_by' => rand(1, 10),
                'updated_by' => rand(1, 10),
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'account_id' => 1,
                'pit_id' => 1,
                'location_type_id' => 4,
                'name' => 'Dumping Area A',
                'geom_type' => 'Point',
                'geom' => 'POINT(115.306628569091004 -3.72440761180557)',
                'radius' => 50,
                'uid' => Str::uuid(),
                'created_by' => rand(1, 10),
                'updated_by' => rand(1, 10),
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'account_id' => 1,
                'pit_id' => 1,
                'location_type_id' => 3,
                'name' => 'Geo PIT A',
                'geom_type' => 'Polygon',
                'geom' => 'POLYGON((115.313374657517997 -3.7228595597198, 115.315290021641005 -3.72381157063944, 115.317562868306993 -3.72337503734797, 115.318935201518997 -3.72133322954022, 115.318524408304 -3.71988028912967, 115.317035267067993 -3.71801110620927, 115.315929578726994 -3.71928996157457, 115.314188273772004 -3.72040529440804, 115.313374657517997 -3.7228595597198))',
                'radius' => null,
                'uid' => Str::uuid(),
                'created_by' => rand(1, 10),
                'updated_by' => rand(1, 10),
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
        ]);
    }
}

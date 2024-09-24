<?php

declare(strict_types=1);

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) {
            DB::table('locations')->insert([
                'account_id' => $faker->numberBetween(1, 10), // ID akun acak antara 1 dan 10
                'pit_id' => $faker->numberBetween(1, 10), // ID pit acak antara 1 dan 10
                'location_type_id' => $faker->numberBetween(1, 5), // ID tipe lokasi acak antara 1 dan 5
                'name' => $faker->streetName, // Nama lokasi diambil dari nama jalan
                'geom_type' => $faker->randomElement(['Polygon', 'Point']), // Tipe geometri acak
                'geom' => $this->generateGeom($faker), // Fungsi untuk menghasilkan geometri
                'radius' => $faker->randomFloat(2, 1, 100), // Jari-jari antara 1 dan 100 dengan 2 desimal
                'uid' => Str::uuid(), // UUID unik untuk setiap lokasi
                'created_by' => $faker->numberBetween(1, 10), // ID pengguna yang membuat lokasi
                'updated_by' => $faker->numberBetween(1, 10), // ID pengguna yang memperbarui lokasi
                'created_at' => now(), // Waktu dibuat
                'updated_at' => now(), // Waktu diperbarui
                'deleted_at' => null, // Tidak ada yang dihapus
            ]);
        }
    }

    private function generateGeom($faker)
    {
        // Generate geometri sesuai dengan tipe
        return $faker->randomElement([
            'POLYGON((30 10, 40 40, 20 40, 10 20, 30 10))', // Contoh poligon
            'POINT(30 10)', // Contoh titik
        ]);
    }
}

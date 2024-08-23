<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Laravel\Passport\Passport;

class PassportKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! file_exists(storage_path('oauth-public.key')) || ! file_exists(storage_path('oauth-private.key'))) {
            Passport::keys();
        }
    }
}

<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Laravel\Passport\ClientRepository;

class PassportClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientRepository = new ClientRepository;

        // Generate Personal Access Client
        $personalAccessClient = $clientRepository->createPersonalAccessClient(
            null,
            'Personal Access Client',
            config('app.url')
        );

        // Generate Password Grant Client
        $passwordGrantClient = $clientRepository->createPasswordGrantClient(
            null,
            'Password Grant Client',
            config('app.url')
        );
    }
}

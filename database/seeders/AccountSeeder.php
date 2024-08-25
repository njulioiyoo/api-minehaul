<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 10; $i++) {
            Account::create([
                'company_code' => 'MIN' . str_pad((string)$i, 3, '0', STR_PAD_LEFT),
                'company_name' => $faker->company,
            ]);
        }
    }
}

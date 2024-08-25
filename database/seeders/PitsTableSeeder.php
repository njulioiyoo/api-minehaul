<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Pit;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class PitsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $accounts = Account::all();

        foreach ($accounts as $account) {
            Pit::create([
                'account_id' => $account->id,
                'name' => $faker->word . ' Pit',
                'description' => $faker->sentence,
                'status' => $faker->randomElement(['active', 'inactive', 'nullified']),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}

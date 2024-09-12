<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Pit;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PitsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $accounts = Account::all();

        foreach ($accounts as $account) {
            Pit::create([
                'account_id' => $account->id,
                'name' => $faker->word.' Pit',
                'description' => $faker->sentence,
                'uid' => Str::uuid()->toString(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}

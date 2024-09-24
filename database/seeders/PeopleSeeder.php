<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Account;
use App\Models\People;
use Illuminate\Database\Seeder;

class PeopleSeeder extends Seeder
{
    public function run(): void
    {
        // Example Account Data (Mining related)
        $accounts = [
            ['company_code' => 'MIN001', 'company_name' => 'Golden Rock Mining Co.'],
            ['company_code' => 'MIN002', 'company_name' => 'Black Diamond Extraction Ltd.'],
            ['company_code' => 'MIN003', 'company_name' => 'Iron Mountain Resources'],
        ];

        // Insert Account Data
        foreach ($accounts as $accountData) {
            $account = Account::create($accountData);

            // Example Person Data
            $people = [
                ['full_name' => 'John Miner', 'account_id' => $account->id],
                ['full_name' => 'Sara Digger', 'account_id' => $account->id],
                ['full_name' => 'Mike Shovel', 'account_id' => $account->id],
            ];

            // Insert Person Data
            foreach ($people as $personData) {
                People::create($personData);
            }
        }
    }
}

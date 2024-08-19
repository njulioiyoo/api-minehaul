<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            ['company_code' => 'MIN001', 'company_name' => 'Golden Rock Mining Co.'],
            ['company_code' => 'MIN002', 'company_name' => 'Black Diamond Extraction Ltd.'],
            ['company_code' => 'MIN003', 'company_name' => 'Iron Mountain Resources'],
        ];

        foreach ($accounts as $account) {
            Account::create($account);
        }
    }
}

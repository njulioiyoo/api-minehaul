<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\Account;
use App\Models\Pit;

trait HasAccountAndPit
{
    public function account()
    {
        return $this->belongsTo(Account::class)->select('id', 'company_code', 'company_name');
    }

    public function pit()
    {
        return $this->belongsTo(Pit::class, 'pit_id')->select('id', 'name', 'description');
    }
}

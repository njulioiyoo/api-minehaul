<?php

declare(strict_types=1);

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasAccountInfo
{
    protected static function bootHasAccountInfo()
    {
        // Global scope untuk memfilter berdasarkan account_id
        static::addGlobalScope('account', function (Builder $builder) {
            if (auth()->check()) {
                $accountId = auth()->user()->persons->account_id;
                $builder->where('account_id', $accountId);
            }
        });
    }
}

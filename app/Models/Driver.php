<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasAccountAndPit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Driver extends Model
{
    use HasAccountAndPit;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'drivers';

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($driver) {
            $user = auth()->user();

            if ($user) {
                $account = $user?->persons?->account;
                if ($account) {
                    $driver->account_id = $account->id;
                }
            }

            $driver->uid = $driver->exists ? $driver->uid : Str::uuid()->toString();
            $driver->{$driver->exists ? 'updated_by' : 'created_by'} = auth()->user()->id;
        });

        static::saved(function ($device) {
            // Logic tambahan setelah disimpan, jika diperlukan
        });
    }
}

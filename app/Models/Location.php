<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Location extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'locations';

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($location) {
            $user = auth()->user();

            if ($user) {
                $account = $user?->persons?->account;
                if ($account) {
                    $location->account_id = $account->id;
                }
            }

            $location->uid = $location->exists ? $location->uid : Str::uuid()->toString();
            $location->{$location->exists ? 'updated_by' : 'created_by'} = auth()->user()->id;
        });

        static::saved(function ($location) {
            // Logic tambahan setelah disimpan, jika diperlukan
        });
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id', 'id')->select('id', 'company_code', 'company_name');
    }

    public function pit()
    {
        return $this->belongsTo(Pit::class, 'pit_id', 'id')->select('id', 'name', 'description');
    }
}

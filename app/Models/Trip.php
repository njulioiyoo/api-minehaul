<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasAccountAndPit;
use App\Models\Traits\HasAccountInfo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Trip extends Model
{
    use HasAccountAndPit;
    use HasAccountInfo;
    use HasFactory;

    protected $table = 'trips';

    protected $guarded = [];

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($device) {
            $user = auth()->user();

            if ($user) {
                $account = $user?->people?->account;
                if ($account) {
                    $device->account_id = $account->id;
                }
            }

            // Set default device_status_id to 1 if it is null
            // $device->device_status_id ??= 1;

            // $device->uid = $device->exists ? $device->uid : Str::uuid()->toString();
            // $device->{$device->exists ? 'updated_by' : 'created_by'} = auth()->user()->id;
        });

        static::saved(function ($device) {
            // Logic tambahan setelah disimpan, jika diperlukan
        });
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Device extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'uid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'devices';
    protected $guarded = [];

    public function getRouteKeyName(): string
    {
        return 'uid';
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($device) {
            $user = auth()->user();

            if ($user) {
                $person = $user->persons;
                if ($person) {
                    $device->account_id = $person->account_id;
                }
            }
            $device->uid = $device->exists ? $device->uid : Str::uuid()->toString();
            $device->{$device->exists ? 'updated_by' : 'created_by'} = auth()->user()->id;
        });

        static::saved(function ($device) {
            // Logic tambahan setelah disimpan, jika diperlukan
        });
    }

    public function account()
    {
        return $this->belongsTo(Account::class)->select('id', 'company_code', 'company_name');
    }

    public function pit()
    {
        return $this->belongsTo(Pit::class, 'pit_id')->select('id', 'name', 'description');
    }

    public function deviceType()
    {
        return $this->belongsTo(DeviceTypeRef::class, 'device_type_id')->select('id', 'name');
    }

    public function deviceMake()
    {
        return $this->belongsTo(DeviceMakeRef::class, 'device_make_id')->select('id', 'name');
    }

    public function deviceModel()
    {
        return $this->belongsTo(DeviceModelRef::class, 'device_model_id')->select('id', 'name');
    }
}

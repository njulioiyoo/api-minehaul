<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Device\DeviceIgnitionType;
use App\Models\Device\DeviceImmobilizitationType;
use App\Models\Device\DeviceMake;
use App\Models\Device\DeviceModel;
use App\Models\Device\DeviceStatus;
use App\Models\Device\DeviceType;
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

            // Set default device_status_id to 1 if it is null
            $device->device_status_id ??= 1;

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
        return $this->belongsTo(DeviceType::class, 'device_type_id')->select('id', 'name');
    }

    public function deviceMake()
    {
        return $this->belongsTo(DeviceMake::class, 'device_make_id')->select('id', 'name');
    }

    public function deviceModel()
    {
        return $this->belongsTo(DeviceModel::class, 'device_model_id')->select('id', 'name');
    }

    public function deviceImmobilizitationType()
    {
        return $this->belongsTo(DeviceImmobilizitationType::class, 'device_immobilizitation_type_id')->select('id', 'name');
    }

    public function deviceIgnitionType()
    {
        return $this->belongsTo(DeviceIgnitionType::class, 'device_ignition_type_id')->select('id', 'name');
    }

    public function deviceStatus()
    {
        return $this->belongsTo(DeviceStatus::class, 'device_status_id')->select('id', 'name');
    }

    public function vehicleId()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id')->select('uid as id', 'name');
    }
}

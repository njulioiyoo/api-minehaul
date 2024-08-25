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

    protected $table = 'devices';

    protected $guarded = [];

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($device) {
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
        return $this->belongsTo(Pit::class, 'pit_id')->select('name', 'description');
    }

    public function deviceType()
    {
        return $this->belongsTo(DeviceTypeRef::class, 'device_type_id')->select('name');
    }

    public function deviceMake()
    {
        return $this->belongsTo(DeviceMakeRef::class, 'device_make_id')->select('name');
    }

    public function deviceModel()
    {
        return $this->belongsTo(DeviceModelRef::class, 'device_model_id')->select('name');
    }
}

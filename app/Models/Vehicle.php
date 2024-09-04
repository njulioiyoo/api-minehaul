<?php

namespace App\Models;

use App\Models\Vehicle\VehicleMake;
use App\Models\Vehicle\VehicleModel;
use App\Models\Vehicle\VehicleStatus;
use App\Models\Vehicle\VehicleType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Vehicle extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'uid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'vehicles';
    protected $guarded = [];

    public function getRouteKeyName(): string
    {
        return 'uid';
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($vehicle) {
            $user = auth()->user();

            if ($user) {
                $person = $user->persons;
                if ($person) {
                    $vehicle->account_id = $person->account_id;
                }
            }

            // Set default vehicle_status to 1 if it is null
            $vehicle->vehicle_status_id ??= 1;

            $vehicle->uid = $vehicle->exists ? $vehicle->uid : Str::uuid()->toString();
            $vehicle->{$vehicle->exists ? 'updated_by' : 'created_by'} = auth()->user()->id;
        });

        static::saved(function ($vehicle) {
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

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id', 'id')->select('id', 'name');
    }

    public function vehicleMake()
    {
        return $this->belongsTo(VehicleMake::class, 'vehicle_make_id', 'id');
    }

    public function vehicleModel()
    {
        return $this->belongsTo(VehicleModel::class, 'vehicle_model_id', 'id');
    }

    public function vehicleStatus()
    {
        return $this->belongsTo(VehicleStatus::class, 'vehicle_status_id', 'id');
    }
}

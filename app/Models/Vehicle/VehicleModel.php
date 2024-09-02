<?php

namespace App\Models\Vehicle;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'vehicle_models';

    public function vehicleMake()
    {
        return $this->belongsTo(VehicleMake::class, 'vehicle_make_id', 'id');
    }
}

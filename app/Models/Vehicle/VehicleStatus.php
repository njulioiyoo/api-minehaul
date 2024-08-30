<?php

namespace App\Models\Vehicle;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleStatus extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'vehicle_statuses';
}

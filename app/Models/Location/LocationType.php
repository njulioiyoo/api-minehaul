<?php

declare(strict_types=1);

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LocationType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'location_types';
}

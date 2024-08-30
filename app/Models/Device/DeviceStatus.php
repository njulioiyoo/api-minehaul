<?php

namespace App\Models\Device;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceStatus extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'device_statuses';
}

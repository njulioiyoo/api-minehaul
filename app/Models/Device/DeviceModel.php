<?php

namespace App\Models\Device;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'device_models';

    public function deviceMake()
    {
        return $this->belongsTo(DeviceMake::class, 'device_make_id', 'id')->select('id', 'name');
    }
}

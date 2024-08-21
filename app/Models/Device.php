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
}

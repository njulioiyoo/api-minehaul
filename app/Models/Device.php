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

    protected $primaryKey = 'device_id';

    protected $table = 'devices';

    protected $guarded = [];

    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($create) {
            $create->dt_creator = auth()->user()->id;
            $create->dt_create_date = date('Y-m-d H:i:s');
            $create->uid = Str::uuid()->toString();
        });

        static::updating(function ($update) {
            $update->dt_editor = auth()->user()->id;
            $update->dt_edit_date = date('Y-m-d H:i:s');
        });
    }
}

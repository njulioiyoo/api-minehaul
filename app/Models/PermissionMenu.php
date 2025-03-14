<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionMenu extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;
}

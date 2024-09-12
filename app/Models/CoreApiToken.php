<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoreApiToken extends Model
{
    use HasFactory;

    protected $table = 'core_api_token';

    protected $fillable = [
        'user_id',
        'session_id',
        'url_call',
        'url_accessed',
        'api_token',
    ];
}

<?php

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
        'api_token',
    ];
}

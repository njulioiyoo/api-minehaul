<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $table = 'accounts';

    protected $guarded = [];

    public function users()
    {
        return $this->hasMany(User::class, 'account_id', 'account_id');
    }
}

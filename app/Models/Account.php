<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;

class Account extends Model
{
    use HasFactory, HasRoles;

    protected $table = 'accounts';

    protected $guarded = [];

    public function users()
    {
        return $this->hasMany(User::class, 'account_id', 'account_id');
    }

    public function pits()
    {
        return $this->hasMany(Pit::class, 'account_id');
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}

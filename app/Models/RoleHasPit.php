<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleHasPit extends Model
{
    use HasFactory;

    protected $table = 'role_has_pits';

    protected $guarded = [];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function pit()
    {
        return $this->belongsTo(Pit::class);
    }
}

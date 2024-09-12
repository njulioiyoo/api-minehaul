<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'drivers';

    protected $guarded = [];

    public function account()
    {
        return $this->belongsTo(Account::class)->select('id', 'company_code', 'company_name');
    }

    public function pit()
    {
        return $this->belongsTo(Pit::class, 'pit_id')->select('id', 'name', 'description');
    }
}

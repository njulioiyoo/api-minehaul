<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $table = 'persons';

    protected $guarded = [];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id', 'id')->select('id', 'company_code', 'company_name', 'uid');
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class People extends Model
{
    use HasFactory;

    protected $table = 'people';

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($people) {
            $auth = auth()->user();

            // $auth->{$auth->exists ? 'updated_by' : 'created_by'} = $auth->id;
        });
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id', 'id')->select('id', 'company_code', 'company_name', 'uid');
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasAccountAndPit;
use App\Models\Traits\HasAccountInfo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasAccountAndPit;
    use HasAccountInfo;
    use HasFactory;

    protected $table = 'trips';

    protected $guarded = [];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}

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

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($device) {
            $user = auth()->user();

            if ($user) {
                $account = $user?->people?->account;
                if ($account) {
                    $device->account_id = $account->id;
                }
            }
        });

        static::saved(function ($device) {
            // Logic tambahan setelah disimpan, jika diperlukan
        });
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function tripType()
    {
        return $this->belongsTo(TripType::class);
    }

    public function tripLoadScanner()
    {
        return $this->belongsTo(TripLoadScanner::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'truck_id', 'id');
    }

    public function device()
    {
        return $this->belongsTo(Device::class, 'load_scanner_id', 'id');
    }
}

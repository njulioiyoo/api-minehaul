<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Location\LocationType;
use App\Models\Traits\HasAccountAndPit;
use App\Models\Traits\HasAccountInfo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Location extends Model
{
    use HasAccountAndPit;
    use HasAccountInfo;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'locations';

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($location) {
            $user = auth()->user();

            if ($user) {
                $account = $user?->people?->account;
                if ($account) {
                    $location->account_id = $account->id;
                }
            }

            // Cek apakah geom_type adalah "Point"
            if ($location->geom_type === 'Point') {
                // Hitung radius otomatis (misalnya berdasarkan logika tertentu)
                // Dalam contoh ini, kita asumsikan radius adalah jarak dari pusat (0,0)
                $location->radius = $location->calculateRadius();
            }

            $location->uid = $location->exists ? $location->uid : Str::uuid()->toString();
            $location->{$location->exists ? 'updated_by' : 'created_by'} = auth()->user()->id;
        });

        static::saved(function ($location) {
            // Logic tambahan setelah disimpan, jika diperlukan
        });
    }

    public function calculateRadius()
    {
        // Ambil nilai x dan y dari geom POINT
        preg_match('/POINT\((\d+)\s(\d+)\)/', $this->geom, $matches);

        if ($matches) {
            $x = (float) $matches[1];
            $y = (float) $matches[2];

            // Menggunakan rumus sederhana jarak Euclidean untuk menghitung radius
            // Jarak dari titik (x, y) ke pusat (0, 0)
            return sqrt(pow($x, 2) + pow($y, 2));
        }

        // Jika tidak bisa menghitung, return null atau default value
        return null;
    }

    public function locationType()
    {
        return $this->belongsTo(LocationType::class, 'location_type_id');
    }

    public function geoLocation()
    {
        return $this->belongsTo(GeoLocation::class, 'id', 'location_id');
    }
}

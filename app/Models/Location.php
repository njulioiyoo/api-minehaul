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

            // Cek apakah geom_type adalah "Polygon"
            if ($location->geom_type === 'Polygon') {
                // Hitung radius otomatis
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
        // Ambil nilai koordinat dari geom POLYGON
        preg_match('/POLYGON\(\((.*?)\)\)/', $this->geom, $matches);
        if ($matches) {
            $coordinates = explode(',', $matches[1]);

            $points = array_map(function ($coordinate) {
                [$x, $y] = explode(' ', trim($coordinate));

                return [(float) $x, (float) $y];
            }, $coordinates);

            // Hitung pusat poligon (rata-rata koordinat)
            $xSum = array_sum(array_column($points, 0));
            $ySum = array_sum(array_column($points, 1));
            $numPoints = count($points);

            $centerX = $xSum / $numPoints;
            $centerY = $ySum / $numPoints;

            // Hitung radius (jarak maksimum dari pusat ke titik-titik poligon)
            $radius = max(array_map(function ($point) use ($centerX, $centerY) {
                return sqrt(pow($point[0] - $centerX, 2) + pow($point[1] - $centerY, 2));
            }, $points));

            return $radius;
        }

        // Jika geom tidak valid, return null
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

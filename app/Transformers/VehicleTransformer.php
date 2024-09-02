<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Vehicle;

class VehicleTransformer
{
    /**
     * Transforms a Device model into an array format.
     *
     * @param Vehicle $vehicle
     * @return array
     */
    public function transform(Vehicle $vehicle): array
    {
        return [
            'type' => 'vehicles',
            'id' => $vehicle->uid,
            'attributes' => [
                'id' => $vehicle->uid,
            ]
        ];
    }
}

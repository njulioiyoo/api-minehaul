<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Location;

class LocationTransformer
{
    /**
     * Transforms a Device model into an array format.
     */
    public function transform(Location $location): array
    {
        return [
            'type' => 'locations',
            'id' => $location->id,
            'attributes' => [
                'id' => $location->id,
                'uid' => $location->uid,
                'account' => $this->transformRelation($location->account, ['id', 'company_code', 'company_name']),
                'pit' => $this->transformRelation($location->pit, ['id', 'name', 'description']),
                'location_type' => $this->transformRelation($location->locationType, ['id', 'name']),
                'name' => $location->name,
                'geom_type' => $location->geom_type,
                'geom' => $location->geom,
                'radius' => $location->radius,
            ],
        ];
    }

    /**
     * Transforms a related model into an array format.
     *
     * @param  mixed  $relation
     */
    private function transformRelation($relation, array $fields): ?array
    {
        if (! $relation) {
            return null;
        }

        return $relation->only($fields);
    }
}

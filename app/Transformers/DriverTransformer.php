<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Driver;

class DriverTransformer
{
    /**
     * Transforms a Driver model into an array format.
     */
    public function transform(Driver $driver): array
    {
        return [
            'type' => 'driver',
            'id' => $driver->uid,
            'attributes' => [
                'id' => $driver->uid,
                'account' => $this->transformRelation($driver->account, ['id', 'company_code', 'company_name']),
                'pit' => $this->transformRelation($driver->pit, ['id', 'name', 'description']),
                'display_id' => $driver->display_id,
                'name' => $driver->name,
                'email' => $driver->email,
                'phone_number' => $driver->phone_number,
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

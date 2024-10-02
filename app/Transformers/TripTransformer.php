<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Trip;
use App\Traits\ExceptionHandlerTrait;

class TripTransformer
{
    use ExceptionHandlerTrait;

    public function transform(Trip $trip): array
    {
        return [
            'type' => 'trips',
            'id' => $trip->id,
            'attributes' => [
                'id' => $trip->id,
                'trip_type_id' => $trip->trip_type_id,
                'driver_id' => $trip->driver_id,
                'truck_id' => $trip->truck_id,
                'excavator_id' => $trip->excavator_id,
                'load_scanner_id' => $trip->load_scanner_id,
                'quantity' => $trip->quantity,
                'trip_start_date' => $trip->trip_start_date,
                'trip_end_date' => $trip->trip_end_date,
                'trip_duration' => $trip->trip_duration,
                'loading_queue_start_date' => $trip->loading_queue_start_date,
                'loading_queue_end_date' => $trip->loading_queue_end_date,
                'loading_queue_duration' => $trip->loading_queue_duration,
                'loading_start_date' => $trip->loading_start_date,
                'loading_end_date' => $trip->loading_end_date,
                'loading_duration' => $trip->loading_duration,
                'dumping_queue_start_date' => $trip->dumping_queue_start_date,
                'dumping_queue_end_date' => $trip->dumping_queue_end_date,
                'dumping_queue_duration' => $trip->dumping_queue_duration,
                'dumping_start_date' => $trip->dumping_start_date,
                'dumping_end_date' => $trip->dumping_end_date,
                'dumping_duration' => $trip->dumping_duration,
                'ref_id' => $trip->ref_id,
                'last_ref_id' => $trip->last_ref_id,
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

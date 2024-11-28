<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Trip;
use App\Traits\ExceptionHandlerTrait;
use Carbon\Carbon;

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
                'account' => $this->transformRelation($trip->account, ['id', 'company_code', 'company_name']),
                'pit' => $this->transformRelation($trip->pit, ['id', 'name', 'description']),
                'trip_type' => $this->transformRelation($trip->tripType, ['id', 'name']),
                'trip_load_scanner' => $this->transformRelation($trip->tripLoadScanner, ['id', 'ticket_no', 'ls_code', 'vehicle_vrm', 'vehicle_size', 'supplier_name', 'operator_name', 'full_scan_at', 'empty_scan_at', 'volume', 'sync_at', 'extras', 'created_at', 'updated_at', 'profile_id', 'user_id', 'material_type']),
                'vehicle' => $this->transformRelation($trip->vehicle, ['id', 'display_id', 'name', 'vin', 'tags', 'license_plate']),
                'device' => $this->transformRelation($trip->device, ['id', 'year', 'display_id', 'name', 'sim_id']),
                'quantity' => $trip->quantity,
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

        $data = $relation->only($fields);

        // Memproses nilai created_at dan updated_at jika ada di array fields
        foreach (['created_at', 'updated_at'] as $dateField) {
            if (isset($data[$dateField])) {
                $data[$dateField] = Carbon::parse($data[$dateField])->format('Y-m-d H:i:s');
            }
        }

        return $data;
    }
}

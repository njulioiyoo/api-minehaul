<?php

declare(strict_types=1);

namespace App\JsonApi\V1\Configurations\Vehicle;

use App\Models\Device;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Schema;

class VehicleSchema extends Schema
{
    /**
     * The model the schema corresponds to.
     */
    public static string $model = Device::class;

    /**
     * Whether resources of this type have a self link.
     *
     * @var bool
     */
    protected bool $selfLink = false;

    protected ?array $defaultPagination = ['number' => 1];

    /**
     * Get the resource fields.
     */
    public function fields(): array
    {
        return [
            ID::make('uid')->uuid(),
            Number::make('pit_id')->sortable(),
            Str::make('year')->sortable(),
            Str::make('display_id')->sortable(),
            Str::make('name')->sortable(),
            Str::make('vin')->sortable(),
            Str::make('license_plate')->sortable(),
            Number::make('vehicle_type_id')->sortable(),
            Number::make('vehicle_make_id')->sortable(),
            Number::make('vehicle_model_id')->sortable(),
            Number::make('vehicle_status_id')->sortable(),
        ];
    }
}

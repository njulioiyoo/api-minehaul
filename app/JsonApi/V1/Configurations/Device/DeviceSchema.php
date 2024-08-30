<?php

declare(strict_types=1);

namespace App\JsonApi\V1\Configurations\Device;

use App\Models\Device;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Filters\Where;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use LaravelJsonApi\Eloquent\Schema;

class DeviceSchema extends Schema
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
            Number::make('device_type_id')->sortable(),
            Number::make('device_make_id')->sortable(),
            Number::make('device_model_id')->sortable(),
            Number::make('year')->sortable(),
            Str::make('display_id')->sortable(),
            Str::make('name')->sortable(),
            Str::make('sim_id')->sortable(),
            Number::make('device_immobilizitation_type_id')->sortable(),
            Number::make('device_ignition_type_id')->sortable(),
            Number::make('vehicle_id')->sortable(),
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\JsonApi\V1\Configurations\Device;

use App\Models\Device;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use LaravelJsonApi\Eloquent\Schema;

class DeviceSchema extends Schema
{
    /**
     * The model the schema corresponds to.
     */
    public static string $model = Device::class;

    protected ?array $defaultPagination = ['number' => 1];

    /**
     * Get the resource fields.
     */
    public function fields(): array
    {
        return [
            ID::make(),
            Number::make('account_id')->sortable(),
            Number::make('pit_id')->sortable(),
            Number::make('device_type_id')->sortable(),
            Str::make('display_id')->sortable(),
            Str::make('name')->sortable(),
            Str::make('sim_id')->sortable(),
            Number::make('year')->sortable(),
            Number::make('device_make_id')->sortable(),
            Number::make('device_model_id')->sortable(),
            Number::make('status_id')->sortable(),
            Str::make('status')->sortable(),
            Number::make('created_by')->sortable(),
            Number::make('updated_by')->sortable(),
            Str::make('uid')->sortable(),
        ];
    }

    /**
     * Get the resource filters.
     */
    public function filters(): array
    {
        return [
            WhereIdIn::make($this),
        ];
    }

    /**
     * Get the resource paginator.
     */
    public function pagination(): ?PagePagination
    {
        return PagePagination::make();
    }
}

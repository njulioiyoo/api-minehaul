<?php

declare(strict_types=1);

namespace App\JsonApi\V1\Configurations\Device;

use App\Models\Device;
use LaravelJsonApi\Eloquent\Contracts\Paginator;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
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
            ID::make('device_id'),
            Str::make('account_id')->sortable(),
            Str::make('device_type_id')->sortable(),
            Str::make('device_display_id')->sortable(),
            Str::make('device_name')->sortable(),
            Str::make('device_sim_id')->sortable(),
            Str::make('device_year')->sortable(),
            Str::make('device_make_id')->sortable(),
            Str::make('device_model_id')->sortable(),
            Str::make('device_status_id')->sortable(),
            Str::make('dt_status')->sortable(),
            Str::make('dt_creator')->sortable(),
            DateTime::make('dt_create_date')->sortable()->readOnly(),
            Str::make('dt_editor')->sortable(),
            DateTime::make('dt_edit_date')->sortable()->readOnly(),
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

<?php

declare(strict_types=1);

namespace App\JsonApi\V1\System\Menu;

use App\Models\Menu;
use Illuminate\Support\Facades\Log;
use LaravelJsonApi\Eloquent\Fields\ArrayList;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use LaravelJsonApi\Eloquent\Schema;

class MenuSchema extends Schema
{
    /**
     * The model the schema corresponds to.
     */
    public static string $model = Menu::class;

    /**
     * Get the resource fields.
     */
    public function fields(): array
    {
        $fields = [
            ID::make(),
            Str::make('name')->sortable(),
            Str::make('key')->sortable(),
            Str::make('icon')->sortable(),
            Str::make('url')->sortable(),
            Number::make('parent_id')->sortable(),
            Number::make('position')->sortable(),
            ArrayList::make('roles')->sortable(),
        ];

        Log::info('MenuSchema fields:', $fields);

        return $fields;
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

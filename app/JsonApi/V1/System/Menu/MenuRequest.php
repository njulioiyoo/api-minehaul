<?php

declare(strict_types=1);

namespace App\JsonApi\V1\System\Menu;

use Illuminate\Support\Facades\Log;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class MenuRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     */
    public function rules(): array
    {
        Log::info('Request Data:', $this->all());

        /** @var \App\Models\Menu|null $model */
        if ($model = $this->model()) {
            return [
                'name' => ['required', 'string'],
                'icon' => ['nullable', 'string'],
                'url' => ['nullable', 'string'],
                'parent_id' => ['nullable', 'integer'],
                'position' => ['required', 'integer'],
                'roles' => ['nullable', 'array'],
            ];
        }

        return [
            'name' => ['required', 'string'],
            'icon' => ['nullable', 'string'],
            'url' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'exists:menus,id'],
            'position' => ['required', 'integer'],
            'roles' => ['nullable', 'array'],
        ];
    }
}

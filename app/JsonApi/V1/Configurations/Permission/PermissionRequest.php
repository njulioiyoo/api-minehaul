<?php

declare(strict_types=1);

namespace App\JsonApi\V1\Configurations\Permission;

use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class PermissionRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     */
    public function rules(): array
    {
        // Periksa apakah permintaan adalah untuk pembaruan atau pembuatan
        $rules = [
            'name' => ['required', 'string', 'max:255'],
        ];

        return $rules;
    }
}

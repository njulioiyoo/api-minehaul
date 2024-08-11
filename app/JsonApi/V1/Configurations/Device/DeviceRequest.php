<?php

declare(strict_types=1);

namespace App\JsonApi\V1\Configurations\Device;

use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class DeviceRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     */
    public function rules(): array
    {
        // Periksa apakah permintaan adalah untuk pembaruan atau pembuatan
        $rules = [
            'account_id' => ['required', 'string', 'max:255'],
            'device_type_id' => ['required', 'integer', 'max:255'],
            'device_display_id' => ['nullable', 'string', 'max:255'],
            'device_name' => ['required', 'string', 'max:255'],
            'device_sim_id' => ['nullable', 'string', 'max:255'],
            'device_year' => ['nullable', 'integer'],
            'device_make_id' => ['nullable', 'integer', 'max:255'],
            'device_model_id' => ['nullable', 'integer', 'max:255'],
            'device_status_id' => ['nullable', 'integer', 'max:255'],
            'dt_status' => ['nullable', 'string', 'max:255'],
        ];

        return $rules;
    }
}

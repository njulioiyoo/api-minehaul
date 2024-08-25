<?php

declare(strict_types=1);

namespace App\JsonApi\V1\Configurations\Device;

use Illuminate\Support\Facades\Log;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class DeviceRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     */
    public function rules(): array
    {
        Log::info('Request Data:', $this->all());

        return [
            'account_id' => ['required', 'integer'],
            'pit_id' => ['nullable', 'integer'],
            'device_type_id' => ['nullable', 'integer'],
            'display_id' => ['nullable', 'string'],
            'name' => ['nullable', 'string'],
            'sim_id' => ['nullable', 'string'],
            'year' => ['nullable', 'integer'],
            'device_make_id' => ['nullable', 'integer'],
            'device_model_id' => ['nullable', 'integer'],
            'status_id' => ['nullable', 'integer'],
            'status' => ['nullable', 'string'],
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\JsonApi\V1\Configurations\Device;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class DeviceRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     */
    public function rules(): array
    {
        /** @var \App\Models\Device|null $model */
        if ($model = $this->model()) {
            return [
                'pit_id' => ['sometimes', 'integer'],
                'device_type_id' => ['sometimes', 'integer'],
                'display_id' => ['sometimes', 'string', 'regex:/^[A-Za-z0-9_-]+$/'],
                'name' => ['sometimes', 'string', 'regex:/^[\p{L}0-9 ]+$/u'],
                'sim_id' => ['sometimes', 'string', 'regex:/^[0-9]{10,20}$/'],
                'year' => ['sometimes', 'integer', 'between:1900,2099'],
                'device_make_id' => ['sometimes', 'integer'],
                'device_model_id' => ['sometimes', 'integer'],
                'status_id' => ['sometimes', 'integer'],
                'status' => ['sometimes', 'string', 'regex:/^(active|inactive)$/'],
            ];
        }

        return [
            'pit_id' => ['nullable', 'integer'],
            'device_type_id' => ['nullable', 'integer'],
            'display_id' => ['nullable', 'string', 'regex:/^[A-Za-z0-9_-]+$/'],
            'name' => ['nullable', 'string', 'regex:/^[\p{L}0-9 ]+$/u'],
            'sim_id' => ['nullable', 'string', 'regex:/^[0-9]{10,20}$/'],
            'year' => ['nullable', 'integer', 'between:1900,2099'],
            'device_make_id' => ['nullable', 'integer'],
            'device_model_id' => ['nullable', 'integer'],
            'status_id' => ['nullable', 'integer'],
            'status' => ['nullable', 'string', 'regex:/^(active|inactive)$/'],
        ];
    }
}

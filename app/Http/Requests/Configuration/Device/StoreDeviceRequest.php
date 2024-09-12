<?php

declare(strict_types=1);

namespace App\Http\Requests\Configuration\Device;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeviceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'pit_id' => ['nullable', 'string', 'uuid'],
            'device_type_id' => ['required', 'integer', 'regex:/^\d+$/'],
            'device_make_id' => ['required', 'integer', 'regex:/^\d+$/'],
            'device_model_id' => ['nullable', 'integer', 'regex:/^\d+$/'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'display_id' => ['required', 'string', 'regex:/^[A-Za-z0-9_-]+$/'],
            'name' => ['required', 'string', 'regex:/^[\p{L}0-9 ]+$/u'],
            'sim_id' => ['nullable', 'string', 'regex:/^[0-9]{10,20}$/'],
            'device_immobilizitation_type_id' => ['required', 'integer', 'regex:/^\d+$/'],
            'device_ignition_type_id' => ['required', 'integer', 'regex:/^\d+$/'],
            'device_status_id' => ['nullable', 'integer', 'regex:/^\d+$/'],
            'vehicle_id' => ['required', 'string', 'uuid'],
        ];
    }
}

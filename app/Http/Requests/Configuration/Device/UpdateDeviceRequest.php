<?php

declare(strict_types=1);

namespace App\Http\Requests\Configuration\Device;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDeviceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'pit_id' => ['nullable', 'integer', 'exists:pits,id'],
            'location_id' => ['nullable', 'integer', 'exists:locations,id'],
            'device_type_id' => ['required', 'integer', 'exists:device_types,id'],
            'device_make_id' => ['required', 'integer', 'exists:device_makes,id'],
            'device_model_id' => ['nullable', 'integer', 'exists:device_models,id'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'display_id' => ['required', 'string', 'regex:/^[A-Za-z0-9_-]+$/'],
            'name' => ['required', 'string', 'regex:/^[\p{L}0-9 ]+$/u'],
            'sim_id' => ['nullable', 'string', 'regex:/^[0-9]{10,20}$/'],
            'device_immobilizitation_type_id' => ['required', 'integer', 'exists:device_immobilizitation_types,id'],
            'device_ignition_type_id' => ['required', 'integer', 'exists:device_ignition_types,id'],
            'device_status_id' => ['nullable', 'integer', 'exists:device_statuses,id'],
            'vehicle_id' => ['required', 'integer', 'exists:vehicles,id'],
        ];
    }
}

<?php

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
        $deviceId = $this->getDeviceId();

        return [
            'pit_id' => ['nullable', 'integer', 'regex:/^\d+$/'],
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
            'uid' => ['sometimes', 'string', 'unique:devices,uid,' . $deviceId],
        ];
    }

    /**
     * Get the ID from the request body.
     *
     * @return string|null
     */
    public function getDeviceId(): ?string
    {
        return $this->input('data.id') ?? $this->route('devices');;
    }
}

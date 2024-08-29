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
            'name' => ['sometimes', 'string', 'max:255'],
            'account_id' => ['sometimes', 'string', 'regex:/^\d+$/'],
            'pit_id' => ['nullable', 'string', 'regex:/^\d+$/'],
            'device_type_id' => ['sometimes', 'string', 'regex:/^\d+$/'],
            'device_make_id' => ['sometimes', 'string', 'regex:/^\d+$/'],
            'device_model_id' => ['nullable', 'string', 'regex:/^\d+$/'],
            'display_id' => ['sometimes', 'string', 'regex:/^[a-zA-Z0-9-]+$/'],
            'sim_id' => ['nullable', 'string', 'regex:/^[a-zA-Z0-9-]+$/'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'status' => ['nullable', 'string', 'in:active,inactive'],
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

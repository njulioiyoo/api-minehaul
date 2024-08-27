<?php

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
            'name' => ['required', 'string', 'regex:/^[\p{L}0-9 ]+$/u'],
            'pit_id' => ['nullable', 'integer', 'regex:/^\d+$/'],
            'device_type_id' => ['required', 'integer', 'regex:/^\d+$/'],
            'device_make_id' => ['required', 'integer', 'regex:/^\d+$/'],
            'device_model_id' => ['nullable', 'integer', 'regex:/^\d+$/'],
            'display_id' => ['required', 'string', 'regex:/^[A-Za-z0-9_-]+$/'],
            'sim_id' => ['nullable', 'string', 'regex:/^[0-9]{10,20}$/'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'status' => ['nullable', 'string', 'in:active,inactive'],
        ];
    }
}

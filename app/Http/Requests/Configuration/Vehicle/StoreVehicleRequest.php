<?php

namespace App\Http\Requests\Configuration\Vehicle;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
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
            'pit_id' => ['nullable', 'integer', 'regex:/^\d+$/'],
            'display_id' => ['required', 'string', 'regex:/^[A-Za-z0-9_-]+$/'],
            'name' => ['required', 'string', 'regex:/^[\p{L}0-9 ]+$/u'],
            'vin' => ['required', 'string', 'regex:/^[\p{L}0-9 ]+$/u'],
            'license_plate' => ['required', 'string', 'regex:/^[\p{L}0-9 ]+$/u'],
            'vehicle_type_id' => ['required', 'integer', 'regex:/^\d+$/'],
            'vehicle_make_id' => ['required', 'integer', 'regex:/^\d+$/'],
            'vehicle_model_id' => ['required', 'integer', 'regex:/^\d+$/'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'vehicle_status_id' => ['nullable', 'integer', 'regex:/^\d+$/'],
        ];
    }
}

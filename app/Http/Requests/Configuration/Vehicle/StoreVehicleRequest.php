<?php

declare(strict_types=1);

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
            'pit_id' => ['nullable', 'integer', 'exists:pits,id'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'display_id' => ['required', 'string', 'regex:/^[A-Za-z0-9_-]+$/'],
            'name' => ['required', 'string', 'regex:/^[\p{L}0-9 ]+$/u'],
            'vin' => ['required', 'string', 'regex:/^[\p{L}0-9 ]+$/u'],
            'tags' => ['nullable', 'string', 'regex:/^([a-zA-Z0-9]+)(,[a-zA-Z0-9]+)*$/'],
            'license_plate' => ['required', 'string', 'regex:/^[\p{L}0-9 ]+$/u'],
            'vehicle_type_id' => ['required', 'integer', 'exists:vehicle_types,id'],
            'vehicle_make_id' => ['required', 'integer', 'exists:vehicle_makes,id'],
            'vehicle_model_id' => ['required', 'integer', 'exists:vehicle_models,id'],
            'vehicle_status_id' => ['nullable', 'integer', 'exists:vehicle_statuses,id'],
        ];
    }
}

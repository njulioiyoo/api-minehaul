<?php

declare(strict_types=1);

namespace App\Http\Requests\Configuration\Location;

use Illuminate\Foundation\Http\FormRequest;

class StoreLocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Ganti dengan logika otorisasi jika diperlukan
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'account_id' => ['required', 'integer', 'exists:accounts,id'],
            'pit_id' => ['nullable', 'integer', 'exists:pits,id'],
            'location_type_id' => ['required', 'integer', 'exists:location_types,id'],
            'name' => ['required', 'string', 'regex:/^[\p{L}0-9 ]+$/u'],
            'geom_type' => ['nullable', 'in:Polygon,Point'],
            'geom' => ['nullable', 'string'],
            'radius' => ['nullable', 'numeric'],
        ];
    }
}

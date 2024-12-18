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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'pit_id' => ['nullable', 'integer', 'exists:pits,id'],
            'location_type_id' => ['required', 'integer', 'exists:location_types,id'],
            'name' => ['required', 'string', 'regex:/^[\p{L}0-9 ]+$/u'],
            'geom_type' => ['required', 'in:Polygon,Point'], // geom_type harus diisi
            'geom' => ['required', 'string', function ($attribute, $value, $fail) {
                $geomType = request()->input('geom_type');
                if ($geomType === 'Polygon' && ! preg_match('/^POLYGON\(\(.*\)\)$/', $value)) {
                    $fail('The '.$attribute.' must be a valid POLYGON format.');
                } elseif ($geomType === 'Point' && ! preg_match('/^POINT\(\d+ \d+\)$/', $value)) {
                    $fail('The '.$attribute.' must be a valid POINT format.');
                }
            }],
        ];

        if ($this->input('geom_type') === 'Point') {
            $rules['radius'] = ['required', 'numeric', 'min:0'];
        }

        return $rules;
    }
}

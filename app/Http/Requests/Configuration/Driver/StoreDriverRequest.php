<?php

declare(strict_types=1);

namespace App\Http\Requests\Configuration\Driver;

use Illuminate\Foundation\Http\FormRequest;

class StoreDriverRequest extends FormRequest
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
            'display_id' => ['required', 'string', 'regex:/^[A-Za-z0-9_-]+$/'],
            'name' => ['required', 'string', 'regex:/^[\p{L}0-9 ]+$/u'],
            'email' => [
                'required',
                'email',
                'regex:/^[\w\.-]+@[\w\.-]+\.[a-zA-Z]{2,6}$/',
                'unique:drivers,email',
            ],
            'phone_number' => ['nullable', 'integer', 'regex:/^\d+$/'],
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Requests\System\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRoleRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'regex:/^[\p{L}0-9 ]+$/u',
                Rule::unique('roles', 'name'),
            ],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['integer'],
            'account_id' => ['required', 'integer'],
            'pit_id' => ['nullable', 'array'],
            'pit_id.*' => ['integer'],
        ];
    }
}

<?php

namespace App\Http\Requests\System\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
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
        $roleId = $this->route('roles');

        return [
            'name' => [
                'required',
                'string',
                'regex:/^[\p{L}0-9 ]+$/u',
                Rule::unique('roles', 'name')->ignore($roleId)
            ],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['integer'],
        ];
    }

    /**
     * Get the role ID from the request body.
     *
     * @return string|null
     */
    public function getRoleId(): ?string
    {
        return $this->input('data.id');
    }
}

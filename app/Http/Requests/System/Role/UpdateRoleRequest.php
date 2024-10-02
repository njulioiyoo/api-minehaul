<?php

declare(strict_types=1);

namespace App\Http\Requests\System\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

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
        $roleId = $this->getRoleId();

        return [
            'name' => [
                'nullable',
                'string',
                'regex:/^[\p{L}0-9 ]+$/u',
                Rule::unique('roles', 'name')->ignore($roleId), // Ignore the unique check for the current role
            ],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['integer'],
            'account_id' => ['required', 'integer'],
            'pit_id' => ['nullable', 'array'],
            'pit_id.*' => ['integer'],
        ];
    }

    /**
     * Get the role ID from the request body or route.
     */
    public function getRoleId(): ?string
    {
        // Ambil ID dari body request terlebih dahulu, jika tidak ada gunakan ID dari route
        return $this->input('data.id') ?? $this->route('roles');
    }
}

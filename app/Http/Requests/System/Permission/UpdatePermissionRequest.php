<?php

namespace App\Http\Requests\System\Permission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePermissionRequest extends FormRequest
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
        $permissionId = $this->route('permission');

        return [
            'name' => [
                'required',
                'string',
                'regex:/^[\p{L}0-9 ]+$/u',
                Rule::unique('permissions', 'name')->ignore($permissionId)
            ],
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

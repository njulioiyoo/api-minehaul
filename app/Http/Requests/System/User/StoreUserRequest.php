<?php

declare(strict_types=1);

namespace App\Http\Requests\System\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'username' => ['required', 'string', 'regex:/^[a-zA-Z0-9._-]{3,20}$/'],
            'full_name' => ['required', 'string'],
            'account_id' => ['required', 'integer'],
            'roles' => ['required', 'array'],
            'roles.*' => ['integer'],
            'email' => [
                'required',
                'email',
                'regex:/^[\w\.-]+@[\w\.-]+\.[a-zA-Z]{2,6}$/',
                'unique:users,email',
            ],
            'password' => ['required', 'confirmed', 'string', 'min:8'],
        ];
    }
}

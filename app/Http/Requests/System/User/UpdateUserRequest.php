<?php

namespace App\Http\Requests\System\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->getUserId();

        return [
            'username' => ['required', 'string', 'regex:/^[a-zA-Z0-9._-]{3,20}$/'],
            'person_id' => ['required', 'string', 'regex:/^\d+$/'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['integer'],
            'email' => [
                'nullable',
                'email',
                'max:255',
                'regex:/^[\w\.-]+@[\w\.-]+\.[a-zA-Z]{2,6}$/',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => ['nullable', 'confirmed', 'string', 'min:8'],
        ];
    }

    /**
     * Get the role ID from the request body or route.
     *
     * @return string|null
     */
    public function getUserId(): ?string
    {
        // Ambil ID dari body request terlebih dahulu, jika tidak ada gunakan ID dari route
        return $this->input('data.id') ?? auth()->id();
    }
}

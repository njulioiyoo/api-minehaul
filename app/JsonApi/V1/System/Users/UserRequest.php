<?php

declare(strict_types=1);

namespace App\JsonApi\V1\System\Users;

use Illuminate\Validation\Rule;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class UserRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     */
    public function rules(): array
    {
        /** @var \App\Models\User|null $model */
        if ($model = $this->model()) {
            return [
                'username' => ['sometimes', 'string', 'regex:/^[a-zA-Z0-9._-]{3,20}$/'],
                'person_id' => ['sometimes', 'string', 'regex:/^\d+$/'],
                'email' => ['sometimes', 'email', 'regex:/^[\w\.-]+@[\w\.-]+\.[a-zA-Z]{2,6}$/', Rule::unique('users')->ignore($model->id)],
                'password' => ['sometimes', 'confirmed', 'string', 'min:8'],
            ];
        }

        return [
            'username' => ['required', 'string', 'regex:/^[a-zA-Z0-9._-]{3,20}$/'],
            'person_id' => ['required', 'string', 'regex:/^\d+$/'],
            'email' => ['required', 'email', 'regex:/^[\w\.-]+@[\w\.-]+\.[a-zA-Z]{2,6}$/', Rule::unique('users')],
            'password' => ['required', 'confirmed', 'string', 'min:8'],
        ];
    }
}

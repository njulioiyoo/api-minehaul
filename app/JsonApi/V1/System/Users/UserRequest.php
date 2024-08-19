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
                'username' => ['sometimes', 'string'],
                'person_id' => ['sometimes', 'string'],
                'email' => ['sometimes', 'email', Rule::unique('users')->ignore($model->id)],
                'password' => ['sometimes', 'confirmed', 'string', 'min:8'],
            ];
        }

        return [
            'username' => ['required', 'string'],
            'person_id' => ['required', 'string'],
            'email' => ['required', 'email', Rule::unique('users')],
            'password' => ['required', 'confirmed', 'string', 'min:8'],
        ];
    }
}

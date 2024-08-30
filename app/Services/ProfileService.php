<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use LaravelJsonApi\Core\Document\Error;
use Illuminate\Validation\ValidationException;
use LaravelJsonApi\Core\Responses\ErrorResponse;

class ProfileService
{
    /**
     * Get the authenticated user's profile.
     *
     * @param int $userId
     * @return User
     */
    public function readProfile(int $userId): User
    {
        // Directly fetch the user profile from the database
        $user = User::find($userId);

        if (!$user) {
            throw new \Exception('User not found', 404);
        }

        return $user;
    }

    /**
     * Update the authenticated user's profile.
     *
     * @param array $data
     * @return User
     * @throws ValidationException
     */
    public function updateProfile(array $data): User
    {
        /** @var User|null $model */
        $model = Auth::user();

        // Define validation rules
        $rules = [
            'username' => ['sometimes', 'string', 'regex:/^[a-zA-Z0-9._-]{3,20}$/'],
            'person_id' => ['sometimes', 'string', 'regex:/^\d+$/'],
            'email' => [
                'sometimes',
                'email',
                'regex:/^[\w\.-]+@[\w\.-]+\.[a-zA-Z]{2,6}$/',
                Rule::unique('users')->ignore($model ? $model->id : null)
            ],
            'password' => ['sometimes', 'confirmed', 'string', 'min:8'],
        ];

        if (!$model) {
            // Make fields required if the user model is not found
            $rules = array_merge($rules, [
                'username' => ['required', 'string', 'regex:/^[a-zA-Z0-9._-]{3,20}$/'],
                'person_id' => ['required', 'string', 'regex:/^\d+$/'],
                'email' => [
                    'required',
                    'email',
                    'regex:/^[\w\.-]+@[\w\.-]+\.[a-zA-Z]{2,6}$/',
                    Rule::unique('users')
                ],
                'password' => ['required', 'confirmed', 'string', 'min:8'],
            ]);
        }

        // Validate the data
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            // Throw a validation exception with JSON:API errors
            throw new ValidationException($validator, $this->formatValidationErrors($validator));
        }

        // Get validated data
        $validatedData = $validator->validated();

        // Update the user's profile
        if ($model) {
            $model->username = $validatedData['username'];
            $model->person_id = $validatedData['person_id'];
            $model->email = $validatedData['email'];

            if (isset($validatedData['password'])) {
                $model->password = bcrypt($validatedData['password']);
            }

            $model->save();
        }

        return $model;
    }

    /**
     * Format validation errors to JSON:API Error objects.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return ErrorResponse
     */
    public function formatValidationErrors($validator): ErrorResponse
    {
        $errors = collect($validator->errors()->toArray())->map(function ($messages, $field) {
            return Error::fromArray([
                'status' => '422',
                'title' => 'Validation Error',
                'detail' => implode(', ', $messages),
                'source' => ['pointer' => "/data/attributes/{$field}"]
            ]);
        });

        return new ErrorResponse($errors);
    }
}

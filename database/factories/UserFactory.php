<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = \App\Models\User::class;

    public function definition()
    {
        return [
            'username' => $this->faker->unique()->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'people_id' => $this->faker->numberBetween(1000, 9999),
        ];
    }

    /**
     * Indicate that the model's password should be hashed.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withPassword($password)
    {
        return $this->state([
            'password' => bcrypt($password),
        ]);
    }
}

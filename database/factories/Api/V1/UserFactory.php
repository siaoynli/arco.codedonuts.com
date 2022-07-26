<?php

namespace Database\Factories\Api\V1;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Api\V1\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $name = fake()->name();
        return [
            "name" => fake()->userName(),
            "password" => '123456',
            "email" => fake()->unique()->safeEmail(),
            "avatar" => 'https://www.gravatar.com/avatar/' . md5(strtolower(trim(fake()->email()))) . '?d=identicon',
            "cn_name" => $name,
            "nick_name" => $name,
            "phone" => fake()->unique()->phoneNumber(),
            'phone_verified_at' => now(),
            'email_verified_at' => now(),
            'is_admin' => 0,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}

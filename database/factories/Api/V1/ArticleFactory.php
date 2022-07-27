<?php

namespace Database\Factories\Api\V1;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "title" => fake()->word,
            "summary" => fake()->sentence(),
            "content" => fake()->text(),
            "author" => fake()->name(),
            "editor" => fake()->name(),
            "user_id" => 1
        ];
    }
}

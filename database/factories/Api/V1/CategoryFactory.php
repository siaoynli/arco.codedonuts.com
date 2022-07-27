<?php

namespace Database\Factories\Api\V1;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        $type = fake()->randomElement([
            "Article",
            "Product",
        ]);

        return [
            'name' => fake()->word(),
            'is_directory' => false,
            'type' => $type,
            'status' => 1,
        ];
    }
}

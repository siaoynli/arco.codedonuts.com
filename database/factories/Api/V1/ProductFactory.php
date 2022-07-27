<?php

namespace Database\Factories\Api\V1;

use App\Models\Api\V1\Category;
use App\Models\Api\V1\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        $image = fake()->randomElement([
            "https://cdn.learnku.com/uploads/images/201806/01/5320/7kG1HekGK6.jpg",
            "https://cdn.learnku.com/uploads/images/201806/01/5320/1B3n0ATKrn.jpg",
            "https://cdn.learnku.com/uploads/images/201806/01/5320/r3BNRe4zXG.jpg",
            "https://cdn.learnku.com/uploads/images/201806/01/5320/C0bVuKB2nt.jpg",
            "https://cdn.learnku.com/uploads/images/201806/01/5320/82Wf2sg8gM.jpg",
            "https://cdn.learnku.com/uploads/images/201806/01/5320/nIvBAQO5Pj.jpg",
            "https://cdn.learnku.com/uploads/images/201806/01/5320/XrtIwzrxj7.jpg",
            "https://cdn.learnku.com/uploads/images/201806/01/5320/uYEHCJ1oRp.jpg",
            "https://cdn.learnku.com/uploads/images/201806/01/5320/2JMRaFwRpo.jpg",
            "https://cdn.learnku.com/uploads/images/201806/01/5320/pa7DrV43Mw.jpg",
        ]);

        // 从数据库中随机取一个类目
        $category = Category::query()->where('is_directory', false)->inRandomOrder()->first();
        $user = User::query()->inRandomOrder()->first();

        return [
            'title' => fake()->word,
            'long_title' => fake()->sentence,
            'summary' => fake()->sentence,
            'image' => $image,
            'on_sale' => true,
            'rating' => fake()->numberBetween(0, 5),
            'sold_count' => 0,
            'review_count' => 0,
            'price' => 0,
            'user_id' => $user->id,
            'category_id' => $category?->id ?: 0,
        ];
    }
}

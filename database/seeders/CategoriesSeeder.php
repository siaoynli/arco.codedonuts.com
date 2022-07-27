<?php

namespace Database\Seeders;

use App\Models\Api\V1\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::truncate();
        Category::factory()->count(30)->create();
    }
}

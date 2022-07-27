<?php

namespace Database\Seeders;

use App\Models\Api\V1\Article;
use Illuminate\Database\Seeder;

class ArticlesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Article::truncate();
        Article::factory()
            ->times(10)
            ->create();

    }
}

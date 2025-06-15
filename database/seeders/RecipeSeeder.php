<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Recipe;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        $categories = Category::all();

        foreach ($categories as $category) {
            Recipe::factory()
                ->count(fake()->numberBetween(8, 12))
                ->create(['category_id' => $category->id]);
        }

        Recipe::inRandomOrder()
            ->limit(15)
            ->update(['is_popular' => true]);
    }
}

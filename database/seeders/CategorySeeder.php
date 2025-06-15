<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => __('Meat Dishes'),
                'slug' => 'meat-dishes',
                'description' => __('Delicious recipes featuring beef, pork, lamb, and other meats'),
                'image' => 'https://via.placeholder.com/400x300?text=Meat+Dishes',
            ],
            [
                'name' => __('Vegetarian'),
                'slug' => 'vegetarian',
                'description' => __('Plant-based recipes without any meat'),
                'image' => 'https://via.placeholder.com/400x300?text=Vegetarian',
            ],
            [
                'name' => __('Seafood'),
                'slug' => 'seafood',
                'description' => __('Fresh fish and seafood recipes'),
                'image' => 'https://via.placeholder.com/400x300?text=Seafood',
            ],
            [
                'name' => __('Desserts'),
                'slug' => 'desserts',
                'description' => __('Sweet treats and desserts for every occasion'),
                'image' => 'https://via.placeholder.com/400x300?text=Desserts',
            ],
            [
                'name' => __('Asian'),
                'slug' => 'asian',
                'description' => __('Traditional and modern Asian cuisine'),
                'image' => 'https://via.placeholder.com/400x300?text=Asian',
            ],
            [
                'name' => __('Italian'),
                'slug' => 'italian',
                'description' => __('Classic Italian dishes and flavors'),
                'image' => 'https://via.placeholder.com/400x300?text=Italian',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

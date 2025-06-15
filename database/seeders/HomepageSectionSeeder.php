<?php

namespace Database\Seeders;

use App\Models\HomepageSection;
use Illuminate\Database\Seeder;

class HomepageSectionSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        $sections = [
            [
                'name' => __('Popular Recipes'),
                'slug' => 'popular-recipes',
                'type' => 'popular',
                'category_slug' => null,
                'order' => 1,
                'visible' => true,
                'limit' => 8,
            ],
            [
                'name' => __('Latest Recipes'),
                'slug' => 'latest-recipes',
                'type' => 'latest',
                'category_slug' => null,
                'order' => 2,
                'visible' => true,
                'limit' => 8,
            ],
            [
                'name' => __('Meat Dishes'),
                'slug' => 'meat-dishes',
                'type' => 'category',
                'category_slug' => 'meat-dishes',
                'order' => 3,
                'visible' => true,
                'limit' => 8,
            ],
        ];

        foreach ($sections as $section) {
            HomepageSection::create($section);
        }
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipe>
 */
class RecipeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->randomElement([
            __('Grilled Chicken Breast'), __('Spaghetti Carbonara'), __('Chocolate Chip Cookies'),
            __('Caesar Salad'), __('Beef Stir Fry'), __('Tomato Soup'), __('Fish Tacos'),
            __('Mushroom Risotto'), __('Apple Pie'), __('Thai Green Curry'), __('BBQ Ribs'),
            __('Caprese Salad'), __('Chicken Noodle Soup'), __('Beef Burgers'), __('Pancakes')
        ]);

        $ingredients = [];
        for ($i = 0; $i < fake()->numberBetween(4, 8); $i++) {
            $ingredients[] = fake()->numberBetween(1, 3) . ' ' . fake()->randomElement([
                __('cups flour'), __('tbsp olive oil'), __('lbs chicken breast'), __('cloves garlic'),
                __('cups milk'), __('eggs'), __('tsp salt'), __('tsp black pepper'), __('onions diced'),
                __('cups cheese grated'), __('tbsp butter'), __('cups vegetable broth')
            ]);
        }

        $instructions = [];
        for ($i = 0; $i < fake()->numberBetween(3, 6); $i++) {
            $instructions[] = fake()->sentence(12);
        }

        return [
            'title' => $title,
            'slug' => \Illuminate\Support\Str::slug($title . '-' . fake()->randomNumber(3)),
            'description' => fake()->paragraph(3),
            'ingredients' => $ingredients,
            'instructions' => $instructions,
            'prep_time' => fake()->numberBetween(10, 60),
            'cook_time' => fake()->numberBetween(15, 120),
            'servings' => fake()->numberBetween(2, 8),
            'difficulty' => fake()->randomElement([__('easy'), __('medium'), __('hard')]),
            'image' => '/storage/images/recipe-placeholder.jpg',
            'is_popular' => fake()->boolean(30),
            'category_id' => \App\Models\Category::factory(),
        ];
    }
}

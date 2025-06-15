<?php

namespace Database\Seeders;

use App\Models\Recipe;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class UpdateRecipeImagesSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        $this->command->info(__('Downloading and setting up recipe placeholder image...'));

        $imageUrl = 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=600&h=400&fit=crop&crop=center&auto=format&q=80';
        $imageName = 'recipe-placeholder.jpg';
        $imagePath = 'images/' . $imageName;

        if (!Storage::disk('public')->exists($imagePath)) {
            $this->command->line(__('Downloading image from Unsplash...'));
            
            try {
                $imageContent = Http::get($imageUrl)->body();
                Storage::disk('public')->put($imagePath, $imageContent);
                $this->command->info('✓' . ' ' . __('Image downloaded and saved to') . ' ' . 'storage/app/public/' . $imagePath);
            } catch (\Exception $e) {
                $this->command->error(__('Failed to download image: ') . $e->getMessage());
                return;
            }
        } else {
            $this->command->line('✓' . ' ' . __('Placeholder image already exists in storage'));
        }

        $localImageUrl = Storage::url($imagePath);
        
        $this->command->info(__('Updating all recipe images with local placeholder...'));
        
        $recipes = Recipe::all();
        $totalRecipes = $recipes->count();
        
        if ($totalRecipes === 0) {
            $this->command->warn(__('No recipes found to update.'));
            return;
        }
        
        $this->command->info(__('Found :total_recipes recipes to update.', ['total_recipes' => $totalRecipes]));

        $bar = $this->command->getOutput()->createProgressBar($totalRecipes);
        $bar->start();
        
        $updatedCount = 0;
        
        foreach ($recipes as $recipe) {
            $recipe->update(['image' => $localImageUrl]);
            $updatedCount++;
            $bar->advance();
        }
        
        $bar->finish();
        $this->command->newLine();
        $this->command->info(__('Successfully updated :updated_count recipe images!', ['updated_count' => $updatedCount]));
        $this->command->line(__('All recipes now use the local placeholder image: ') . $localImageUrl);
        $this->command->line(__('Image stored at: ') . 'storage/app/public/' . $imagePath);
    }
}

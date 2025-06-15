<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recipe extends Model
{
    /** @use HasFactory<\Database\Factories\RecipeFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'ingredients',
        'instructions',
        'prep_time',
        'cook_time',
        'servings',
        'difficulty',
        'image',
        'is_popular',
        'category_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'ingredients' => 'array',
            'instructions' => 'array',
            'is_popular' => 'boolean',
            'prep_time' => 'integer',
            'cook_time' => 'integer',
            'servings' => 'integer',
        ];
    }

    /**
     * Get the category that owns the recipe.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the recipe's total time.
     */
    protected function totalTime(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $attributes['prep_time'] + $attributes['cook_time'],
        );
    }

    /**
     * Scope the query to only include is popular.
     */
    #[Scope]
    protected function popular(Builder $query): void
    {
        $query->where('is_popular', true);
    }

    /**
     * Scope the query to only include latest.
     */
    #[Scope]
    protected function latest(Builder $query): void
    {
        $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope the query to only include by category.
     */
    #[Scope]
    protected function byCategory(Builder $query, string $categorySlug): void
    {
        $query->whereHas('category', function ($q) use ($categorySlug) {
            $q->where('slug', $categorySlug);
        });
    }
}

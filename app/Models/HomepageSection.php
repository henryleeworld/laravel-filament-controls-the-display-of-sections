<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageSection extends Model
{
    /** @use HasFactory<\Database\Factories\HomepageSectionFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'type',
        'category_slug',
        'order',
        'visible',
        'limit',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'visible' => 'boolean',
            'order' => 'integer',
            'limit' => 'integer',
        ];
    }

    /**
     * Scope the query to only include visible.
     */
    #[Scope]
    protected function visible(Builder $query): void
    {
        $query->where('visible', true);
    }

    /**
     * Scope the query to only include ordered.
     */
    #[Scope]
    protected function ordered(Builder $query): void
    {
        $query->orderBy('order');
    }

    public function getRecipes(): Collection
    {
        $query = Recipe::with('category');

        return match($this->type) {
            'popular' => $query->popular()->limit($this->limit)->get(),
            'latest' => $query->latest()->limit($this->limit)->get(),
            'category' => $query->byCategory($this->category_slug)->limit($this->limit)->get(),
            default => collect(),
        };
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomepageSectionResource\Pages;
use App\Models\Category;
use App\Models\HomepageSection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class HomepageSectionResource extends Resource
{
    protected static ?string $model = HomepageSection::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Homepage Sections';

    protected static ?string $modelLabel = 'homepage section';

    protected static ?string $pluralModelLabel = 'homepage sections';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('Name'))
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $context, $state, Forms\Set $set) => $context === 'create' ? $set('slug', Str::slug($state)) : null),

                Forms\Components\TextInput::make('slug')
                    ->label(__('Slug'))
                    ->required()
                    ->unique(HomepageSection::class, 'slug', ignoreRecord: true),

                Forms\Components\Select::make('type')
                    ->label(__('Type'))
                    ->required()
                    ->options([
                        'popular' => __('Popular Recipes'),
                        'latest' => __('Latest Recipes'),
                        'category' => __('Category Based'),
                    ])
                    ->live()
                    ->afterStateUpdated(fn (Forms\Set $set) => $set('category_slug', null)),

                Forms\Components\Select::make('category_slug')
                    ->label(__('Category'))
                    ->options(fn () => Category::pluck('name', 'slug'))
                    ->visible(fn (Forms\Get $get) => $get('type') === 'category')
                    ->required(fn (Forms\Get $get) => $get('type') === 'category'),

                Forms\Components\TextInput::make('order')
                    ->label(__('Order'))
                    ->required()
                    ->numeric()
                    ->default(fn () => HomepageSection::max('order') + 1)
                    ->minValue(1),

                Forms\Components\TextInput::make('limit')
                    ->label(__('Limit'))
                    ->required()
                    ->numeric()
                    ->default(4)
                    ->minValue(1)
                    ->maxValue(20)
                    ->helperText(__('Number of recipes to display in this section')),

                Forms\Components\Toggle::make('visible')
                    ->label(__('Show on Homepage'))
                    ->default(true),
            ]);
    }

    public static function getBreadcrumb(): string
    {
        return __('Homepage section');
    }

    public static function getModelLabel(): string
    {
        return __('homepage section');
    }

    public static function getNavigationLabel(): string
    {
        return __('Homepage Sections');
    }

    public static function getPluralModelLabel(): string
    {
        return __('homepage sections');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHomepageSections::route('/'),
            'create' => Pages\CreateHomepageSection::route('/create'),
            'edit' => Pages\EditHomepageSection::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->label('#')
                    ->sortable()
                    ->width(60),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->label(__('Type'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'popular' => 'success',
                        'latest' => 'info',
                        'category' => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'popular' => __('Popular'),
                        'latest' => __('Latest'),
                        'category' => __('Category'),
                    }),

                Tables\Columns\TextColumn::make('category_slug')
                    ->label(__('Category'))
                    ->getStateUsing(function ($record) {
                        if ($record->type === 'category' && $record->category_slug) {
                            $category = Category::where('slug', $record->category_slug)->first();
                            return $category?->name ?? $record->category_slug;
                        }
                        return '-';
                    })
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('limit')
                    ->label(__('Limit'))
                    ->alignCenter(),

                Tables\Columns\ToggleColumn::make('visible')
                    ->label(__('Visible'))
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Last modified at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->reorderable('order')
            ->defaultSort('order')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label(__('Type'))
                    ->options([
                        'popular' => __('Popular'),
                        'latest' => __('Latest'),
                        'category' => __('Category'),
                    ]),
                Tables\Filters\TernaryFilter::make('visible')
                    ->label(__('Visibility'))
                    ->placeholder(__('All sections'))
                    ->trueLabel(__('Visible sections'))
                    ->falseLabel(__('Hidden sections')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

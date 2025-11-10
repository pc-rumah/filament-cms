<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Filament\Resources\CategoryResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CategoryResource\RelationManagers;
use Filament\Notifications\Collection;
use Filament\Notifications\Notification;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $recordTitleAttribute = 'slug';

    public static function getNavigationLabel(): string
    {
        return __('resource.category.navigation.label');
    }

    public static function getModelLabel(): string
    {
        return __('resource.category.navigation.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resource.category.navigation.plural_model_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label(__('resource.category.fields.name'))
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, ?string $state) {
                        $slug = Str::slug($state);
                        $set('slug', $slug);
                    }),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->label(__('resource.category.fields.slug'))
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->label(__('resource.category.fields.description')),
                Forms\Components\Toggle::make('active')
                    ->required()
                    ->label(__('resource.category.fields.status')),
                Forms\Components\Select::make('parent_id')
                    ->label(__('resource.category.fields.parent_category'))
                    ->searchable()
                    ->relationship('parent', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label(__('resource.category.fields.name')),

                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->label(__('resource.category.fields.slug')),

                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->label(__('resource.category.fields.description')),

                Tables\Columns\IconColumn::make('active')
                    ->boolean()
                    ->label(__('resource.category.fields.status')),

                Tables\Columns\TextColumn::make('parent.name')
                    ->numeric()
                    ->label(__('resource.category.fields.parent_category')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('toogle_status')
                        ->label(__("resource.category.action.toggle"))
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->active = !$record->active;
                                $record->save();
                            }
                            Notification::make()
                                ->title(__('resource.notification.success.default_title'))
                                ->body(__('resource.notification.success.default_body'))
                                ->success()
                                ->send();
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}

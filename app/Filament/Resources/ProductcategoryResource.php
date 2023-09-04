<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductcategoryResource\Pages;
use App\Filament\Resources\ProductcategoryResource\RelationManagers;
use App\Models\Productcategory;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ProductcategoryResource extends Resource
{
    protected static ?string $model = Productcategory::class;
    protected static ?string $modelLabel= "Hardware Category";

    protected static ?string $navigationGroup = 'Product Management';
    protected static ?string $navigationGroupIcon = 'heroicon-o-collection';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Category';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('product_category_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('product_category_description')
                            ->maxLength(65535),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('product_brand'),
                // Tables\Columns\TextColumn::make('product_inventory'),
                Tables\Columns\TextColumn::make('product_category_name')
                    ->label('Category Name')
                    ->limit(20)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('product_category_description')
                    ->label('Category Description')
                    ->limit(50),
                // Tables\Columns\TextColumn::make('product_created_at')
                //     ->dateTime(),
                // Tables\Columns\TextColumn::make('product_modified_at')
                //     ->dateTime(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListProductcategories::route('/'),
            'create' => Pages\CreateProductcategory::route('/create'),
            'edit' => Pages\EditProductcategory::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        $allowed_array = ['Superadmin', 'Administrator'];
        if (in_array(Auth::user()->role->role_name, $allowed_array)) {
            return true;
        }
        return false;
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductbrandResource\Pages;
use App\Filament\Resources\ProductbrandResource\RelationManagers;
use App\Models\Productbrand;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ProductbrandResource extends Resource
{
    protected static ?string $model = Productbrand::class;

    protected static ?string $navigationGroup = 'Product Management';
    protected static ?string $navigationGroupIcon = 'heroicon-o-collection';

    protected static ?int $navigationSort = 0;

    protected static ?string $navigationLabel = 'Brand';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('product_brand_name')
                            ->required()
                            ->maxLength(255)
                            ->label("Brand Name"),
                        Forms\Components\Textarea::make('product_brand_description')
                            ->maxLength(65535)
                            ->label("Brand Description"),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product_brand_name')
                    ->limit(50)
                    ->searchable()
                    ->sortable()
                    ->label('Brand Name'),
                Tables\Columns\TextColumn::make('product_brand_description')
                    ->limit(50)
                    ->label('Brand Description'),
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
            'index' => Pages\ListProductbrands::route('/'),
            'create' => Pages\CreateProductbrand::route('/create'),
            'edit' => Pages\EditProductbrand::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        // use Illuminate\Support\Facades\Auth;

        // Moderator check
        if(Auth::user()->role->role_name == 'Moderator') {
            return false;
        }

        return true;
    }
}

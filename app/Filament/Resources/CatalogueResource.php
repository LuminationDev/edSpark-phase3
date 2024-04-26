<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CatalogueResource\Pages;
use App\Filament\Resources\CatalogueResource\RelationManagers;
use App\Models\Catalogue;
use App\Models\Catalogueversion;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Outerweb\FilamentImageLibrary\Filament\Forms\Components\ImageLibraryPicker;

class CatalogueResource extends Resource
{
    protected static ?string $model = Catalogue::class;
    protected static ?string $navigationGroup = 'Product Management';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('unique_reference'),
                TextInput::make('type'),
                TextInput::make('brand'),
                TextInput::make('name'),
                TextInput::make('vendor'),
                TextInput::make('category'),
                TextInput::make('price_inc_gst'),
                TextInput::make('processor'),
                TextInput::make('storage'),
                TextInput::make('memory'),
                TextInput::make('form_factor'),
                TextInput::make('display'),
                TextInput::make('graphics'),
                TextInput::make('wireless'),
                TextInput::make('webcam'),
                TextInput::make('operating_system'),
                TextInput::make('warranty'),
                TextInput::make('battery_life'),
                TextInput::make('weight'),
                TextInput::make('stylus'),
                TextInput::make('other'),
                TextInput::make('available_now'),
                TextInput::make('corporate'),
                TextInput::make('administration'),
                TextInput::make('curriculum'),
                TextInput::make('image'),
                TextInput::make('product_number'),
                TextInput::make('price_expiry'),
                ImageLibraryPicker::make('cover_image')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->limit(30)
                    ->searchable(),
                Tables\Columns\TextColumn::make('version_id')
                    ->label('Version')
                    ->limit(3),
                Tables\Columns\TextColumn::make('vendor')
                    ->label('Vendor')
                    ->limit(30)->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('brand')
                    ->label('Brand')
                    ->limit(30)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('category')
                    ->label('Category')
                    ->limit(50)
                    ->sortable()
                    ->searchable(),

            ])
            ->modifyQueryUsing(function (Builder $query) {
                return $query->where('version_id', Catalogueversion::getActiveCatalogueId());
            })
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])
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
            'index' => Pages\ListCatalogues::route('/'),
            'create' => Pages\CreateCatalogue::route('/create'),
            'edit' => Pages\EditCatalogue::route('/{record}/edit'),
        ];
    }
}

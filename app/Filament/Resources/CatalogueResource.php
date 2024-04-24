<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CatalogueResource\Pages;
use App\Filament\Resources\CatalogueResource\RelationManagers;
use App\Models\Catalogue;
use App\Models\Catalogueversion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CatalogueResource extends Resource
{
    protected static ?string $model = Catalogue::class;
    protected static ?string $navigationGroup = 'Product Management';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
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

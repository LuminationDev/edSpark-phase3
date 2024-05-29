<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CatalogueversionResource\Pages;
use App\Filament\Resources\CatalogueversionResource\RelationManagers;
use App\Models\Catalogueversion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CatalogueversionResource extends Resource
{
    protected static ?string $model = Catalogueversion::class;
    protected static ?string $modelLabel = 'Catalogue version';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Classroom Catalogue';
    protected static ?string $navigationLabel = 'Catalogue versions';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\FileUpload::make('csv_file')
                        ->acceptedFileTypes(['text/csv'])
                        ->storeFiles(false)
                        ->required(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('version')
                    ->label('Version')
                    ->limit(25)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Is Active')
                    ->disabled(fn($record) => $record->is_active)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime()
                    ->label('Created at'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->sortable()
                    ->dateTime()
                    ->label('Last Updated'),
            ])
            ->defaultSort('version', 'desc');
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
            'index' => Pages\ListCatalogueversions::route('/'),
            'create' => Pages\CreateCatalogueversion::route('/create'),
        ];
    }
}

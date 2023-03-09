<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SoftwaretypeResource\Pages;
use App\Filament\Resources\SoftwaretypeResource\RelationManagers;
use App\Models\Softwaretype;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SoftwaretypeResource extends Resource
{
    protected static ?string $model = Softwaretype::class;

    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationGroupIcon = 'heroicon-o-collection';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('software_type_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('software_type_value')
                            ->required()
                            ->maxLength(65535),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('software_type_name')
                    ->sortable()
                    ->searchable()
                    ->label('Name'),
                Tables\Columns\TextColumn::make('software_type_value')
                    ->label('Value'),
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
            'index' => Pages\ListSoftwaretypes::route('/'),
            'create' => Pages\CreateSoftwaretype::route('/create'),
            'edit' => Pages\EditSoftwaretype::route('/{record}/edit'),
        ];
    }
}

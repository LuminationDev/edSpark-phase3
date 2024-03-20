<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventtypeResource\Pages;
use App\Filament\Resources\EventtypeResource\RelationManagers;
use App\Helpers\RoleHelpers;
use App\Models\Eventtype;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class EventtypeResource extends Resource
{
    protected static ?string $model = Eventtype::class;
    protected static ?string $modelLabel= "Event Type";

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationGroupIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Event types';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('event_type_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('event_type_value')
                            ->maxLength(65535),
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event_type_name')
                    ->label('name'),
                Tables\Columns\TextColumn::make('event_type_value')
                    ->label('value')
                    ->limit(50),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('j M y, h:i a'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('j M y, h:i a'),
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
            'index' => Pages\ListEventtypes::route('/'),
            'create' => Pages\CreateEventtype::route('/create'),
            'edit' => Pages\EditEventtype::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return RoleHelpers::has_minimum_privilege('admin');
    }

}

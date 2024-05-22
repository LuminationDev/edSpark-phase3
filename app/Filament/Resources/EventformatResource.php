<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventformatResource\Pages;
use App\Filament\Resources\EventformatResource\RelationManagers;
use App\Helpers\RoleHelpers;
use App\Models\Eventformat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EventformatResource extends Resource
{
    protected static ?string $model = Eventformat::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $modelLabel= "Event format";
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationGroupIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Event formats';

    protected static ?int $navigationSort = 4;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('event_format_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('event_format_value')
                            ->maxLength(65535),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event_format_name')
                    ->label('name'),
                Tables\Columns\TextColumn::make('event_format_value')
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListEventformats::route('/'),
            'create' => Pages\CreateEventformat::route('/create'),
            'edit' => Pages\EditEventformat::route('/{record}/edit'),
        ];
    }
    public static function shouldRegisterNavigation(): bool
    {
        return RoleHelpers::has_minimum_privilege('admin');
    }

}

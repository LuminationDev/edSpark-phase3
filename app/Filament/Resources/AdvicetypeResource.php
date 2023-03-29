<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdvicetypeResource\Pages;
use App\Filament\Resources\AdvicetypeResource\RelationManagers;
use App\Models\Advicetype;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Illuminate\Support\Facades\Auth;

class AdvicetypeResource extends Resource
{
    protected static ?string $model = Advicetype::class;

    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationGroupIcon = 'heroicon-o-collection';

    protected static ?int $navigationSort = 0;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationLabel = 'Advice types';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('advice_type_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('advice_type_value')
                            ->required()
                            ->maxLength(65535),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('advice_type_name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('advice_type_value')
                    ->label('Value')
                    ->limit(50),
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
            'index' => Pages\ListAdvicetypes::route('/'),
            'create' => Pages\CreateAdvicetype::route('/create'),
            'edit' => Pages\EditAdvicetype::route('/{record}/edit'),
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

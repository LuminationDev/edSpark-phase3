<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommunitytypeResource\Pages;
use App\Filament\Resources\CommunitytypeResource\RelationManagers;
use App\Models\Communitytype;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Illuminate\Support\Facades\Auth;

class CommunitytypeResource extends Resource
{
    protected static ?string $model = Communitytype::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationGroupIcon = 'heroicon-o-collection';

    protected static ?int $navigationSort = 6;
    protected static ?string $navigationLabel = 'Community types';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('community_type_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('community_type_value')
                            ->maxLength(65535),
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('community_type_name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('community_type_value')
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
            'index' => Pages\ListCommunitytypes::route('/'),
            'create' => Pages\CreateCommunitytype::route('/create'),
            'edit' => Pages\EditCommunitytype::route('/{record}/edit'),
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

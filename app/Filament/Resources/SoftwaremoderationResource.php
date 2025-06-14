<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SoftwaremoderationResource\Pages;
use App\Filament\Resources\SoftwaremoderationResource\RelationManagers;
use App\Helpers\RoleHelpers;
use App\Helpers\StatusHelpers;
use App\Helpers\UserRole;
use App\Models\Softwaremoderation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class SoftwaremoderationResource extends Resource
{
    protected static ?string $model = Softwaremoderation::class;
    protected static ?string $modelLabel= "Software Moderation";


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Moderation';
    protected static ?string $navigationLabel = 'Software Moderation';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Card::make()->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->options(StatusHelpers::getStatusList())
                    ->label('Status')
                    ->required(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('softwaretype.software_type_name')
                    ->label('Type')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('author.full_name')->label('Author'),
                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->label('Created At'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->date()
                    ->label('Modified At'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListSoftwaremoderations::route('/'),
            'create' => Pages\CreateSoftwaremoderation::route('/create'),
            'edit' => Pages\EditSoftwaremoderation::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('status', StatusHelpers::PENDING);
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::query()->where('status', StatusHelpers::PENDING)->count();
        if ($count > 0){
            return $count;
        }else{
            return '';
        }
    }

    public static function shouldRegisterNavigation(): bool
    {
        return RoleHelpers::has_minimum_privilege(UserRole::MODERATOR);
    }

}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use App\Filament\Resources\PermissionResource\RelationManagers\RolesRelationManager;
use App\Helpers\RoleHelpers;
use App\Helpers\UserRole;
use App\Models\Permission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use App\Models\User;
use Illuminate\Support\Facades\Auth;


class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static ?string $navigationGroup = 'User Management';

    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('permission_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('permission_value')
                            ->maxLength(65535),
                            ]),

                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\CheckboxList::make('roles')
                            ->label('Associated Roles')
                            ->extraAttributes(['class' => 'text-primary-600'])
                            ->relationship('roles', 'role_name', fn (Builder $query) => $query->where('role_name', '!=', 'superadmin'))
                            ->columns(4)
                            ->bulkToggleable()
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('permission_name')->label('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('permission_value')->label('value')->limit(50),
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
            RolesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return RoleHelpers::has_minimum_privilege(UserRole::GODMODE);

    }

}

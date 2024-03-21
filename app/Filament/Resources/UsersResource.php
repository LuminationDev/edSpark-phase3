<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UsersResource\Pages;
use App\Filament\Resources\UsersResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class UsersResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $modelLabel = 'User';

    protected static ?string $navigationGroup = 'User Management';
    protected static ?string $navigationGroupIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('full_name')
                            ->required(),
                        Forms\Components\TextInput::make('display_name')
                            ->required(),
                        Forms\Components\TextInput::make('email')->email()
                            ->required(),
                        Forms\Components\TextInput::make('password')
                            ->default('edSparkDigitalHub')
                            ->helperText('**password to be changed on login')
                            ->readOnly(),
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\BelongsToSelect::make('role_id')
                                    ->relationship('role', 'role_name'),
                                Forms\Components\BelongsToSelect::make('site_id')
                                    ->relationship('site', 'site_name'),
                                Forms\Components\Select::make('status')
                                    ->options([
                                                'Active' => 'Active',
                                                'Inactive' => 'Inactive',
                                                ])
                                    ->required(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('display_name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('status')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('role.role_name'),
                Tables\Columns\TextColumn::make('site.site_name'),
                // Tables\Columns\TextColumn::make('type_id')

            ])
            ->filters([
                // Filter::make('status')->label('Status')
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUsers::route('/create'),
            'edit' => Pages\EditUsers::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        $allowed_array = ['Superadmin', 'Administrator'];
        if (in_array(Auth::user()->role->role_name, $allowed_array)) {
            return true;
        }
        return false;
    }
}

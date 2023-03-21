<?php

namespace App\Filament\Resources;

use Filament\Facades\Filament;
use App\Filament\Resources\RolesResource\Pages;
// use App\Filament\Resources\RolesResource\RelationManagers;
use App\Models\Role;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Closure;

use App\Filament\Resources\RolesResource\RelationManagers\PermissionsRelationManager;
use Illuminate\Database\Eloquent\Model;

class RolesResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationGroup = 'User Management';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';


    // Backup
    // public static function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             Forms\Components\Card::make()
    //                 ->schema([
    //                     Forms\Components\TextInput::make('role_name')->label('Role Name')
    //                         ->maxLength(255)
    //                         ->required(),
    //                     Forms\Components\Textarea::make('role_value')->label('Role Value')
    //                         ->maxLength(65535),
    //                 ]),

    //                 Forms\Components\Grid::make()
    //                     ->schema([
    //                         Forms\Components\Card::make()
    //                             ->schema([
    //                                 Forms\Components\Toggle::make('select_all')
    //                                     ->onIcon('heroicon-s-shield-check')
    //                                     ->offIcon('heroicon-s-shield-exclamation')
    //                                     ->label('Select All')
    //                                     ->helperText('Enable all Permissions for this role.')
    //                                     ->reactive()
    //                                     ->afterStateUpdated(function (Closure $set, $state) {
    //                                         foreach (static::getEntities() as $entity) {
    //                                             $set($entity, $state);
    //                                             foreach(static::getPermissions() as $perm)
    //                                             {
    //                                                 $set($entity.'_'.$perm, $state);
    //                                             }
    //                                         }

    //                                     })
    //                             ]),
    //                     ]),
    //                 Forms\Components\Grid::make([
    //                     'sm' => 2,
    //                     'lg' => 3,
    //                 ])
    //                 ->schema(static::getEntitySchema())
    //                 ->columns([
    //                     'sm' => 2,
    //                     'lg' => 3
    //                 ])

    //         ]);
    // }

    // protected static function getEntities(): ?array
    // {
    //     return collect(Filament::getResources())
    //         ->reduce(function ($options, $resource) {
    //             $option = Str::before(Str::afterLast($resource,'\\'),'Resource');
    //             $options[$option] = $option;
    //             return $options;
    //         }, []);
    // }

    // protected static function getPermissions(): array
    // {
    //     return ['create','view', 'update','delete'];
    // }

    // public static function getEntitySchema()
    // {
    //     return collect(static::getEntities())->reduce(function($entities,$entity) {
    //             $entities[] = Forms\Components\Card::make()
    //                 ->schema([
    //                     Forms\Components\Toggle::make($entity)
    //                         ->onIcon('heroicon-s-lock-open')
    //                         ->offIcon('heroicon-s-lock-closed')
    //                         ->reactive()
    //                         ->afterStateUpdated(function (Closure $set,Closure $get, $state) use($entity) {

    //                             collect(static::getPermissions())->each(function ($permission) use($set, $entity, $state) {
    //                                     $set($entity.'_'.$permission, $state);
    //                             });

    //                             if (! $state) {
    //                                 $set('select_all',false);
    //                             }

    //                             $entityStates = [];
    //                             foreach(static::getEntities() as $ent) {
    //                                 $entityStates [] = $get($ent);
    //                             }

    //                             if (in_array(false,$entityStates, true) === false) {
    //                                 $set('select_all', true); // if all toggles on => turn select_all on
    //                             }

    //                             if (in_array(true,$entityStates, true) === false) {
    //                                 $set('select_all', false); // if even one toggle off => turn select_all off
    //                             }
    //                         }),
    //                     Forms\Components\Fieldset::make('Permissions')
    //                     ->extraAttributes(['class' => 'text-primary-600','style' => 'border-color:var(--primary)'])
    //                     ->columns([
    //                         'default' => 2,
    //                         'xl' => 2
    //                     ])
    //                     ->schema(static::getPermissionsSchema($entity))
    //                 ])
    //                 ->columns(2)
    //                 ->columnSpan(1);
    //             return $entities;
    //     },[]);
    // }

    // public static function getPermissionsSchema($entity)
    // {
    //     return collect(static::getPermissions())->reduce(function ($permissions, $permission) use ($entity) {
    //         $permissions[] = Forms\Components\Checkbox::make($entity.'_'.$permission)
    //                             ->label($permission)
    //                             ->extraAttributes(['class' => 'text-primary-600'])
    //                             ->reactive()
    //                             ->afterStateUpdated(function (Closure $set, Closure $get, $state) use($entity){

    //                                 $permissionStates = [];
    //                                 foreach(static::getPermissions() as $perm) {
    //                                     $permissionStates [] = $get($entity.'_'.$perm);
    //                                 }

    //                                 if (in_array(false,$permissionStates, true) === false) {
    //                                     $set($entity, true); // if all permissions true => turn toggle on
    //                                 }

    //                                 if (in_array(true,$permissionStates, true) === false) {
    //                                     $set($entity, false); // if even one false => turn toggle off
    //                                 }

    //                                 if(!$state) {
    //                                     $set($entity,false);
    //                                     $set('select_all',false);
    //                                 }

    //                                 $entityStates = [];
    //                                 foreach(static::getEntities() as $ent) {
    //                                     $entityStates [] = $get($ent);
    //                                 }

    //                                 if (in_array(false,$entityStates, true) === false) {
    //                                     $set('select_all', true); // if all toggles on => turn select_all on
    //                                 }

    //                                 if (in_array(true,$entityStates, true) === false) {
    //                                     $set('select_all', false); // if even one toggle off => turn select_all off
    //                                 }
    //                             });
    //         return $permissions;
    //     },[]);
    // }

    // public static function table(Table $table): Table
    // {
    //     return $table
    //         ->columns([
    //             // Tables\Columns\TextColumn::make('role_uid')->label("Uid"),
    //             // Tables\Columns\TextColumn::make('role_name')->label('Name'),
    //             // // ->description(fn (Role $record): string => $record->role_value)
    //             // Tables\Columns\TextColumn::make('role_value')->label('Value'),

    //             Tables\Columns\BadgeColumn::make('role_name')
    //                 ->formatStateUsing(fn ($state): string => Str::headline($state))
    //                 ->colors(['primary'])
    //                 ->searchable(),
    //             Tables\Columns\BadgeColumn::make('role_value'),
    //             Tables\Columns\BadgeColumn::make('permissions_count')
    //                 // ->counts('permissions')
    //                 ->colors(['success']),
    //             Tables\Columns\TextColumn::make('updated_at')
    //                 ->dateTime(),


    //         ])
    //         ->filters([
    //             //
    //         ])
    //         ->actions([
    //             Tables\Actions\EditAction::make()->hidden(fn ($record) => $record->role_name === 'Superadmin'),
    //             Tables\Actions\DeleteAction::make()->hidden(fn ($record) => $record->role_name === 'Superadmin'),
    //         ])
    //         ->bulkActions([
    //             Tables\Actions\DeleteBulkAction::make(),
    //         ]);
    // }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('role_name')
                            ->label('Role name')
                            ->required(),
                        Forms\Components\TextInput::make('role_value')
                            ->label('Role value')

                        ]),

                Forms\Components\Card::make()
                    ->schema([
                            Forms\Components\CheckboxList::make('permissions')
                                ->label('Associated Permissions')
                                ->extraAttributes(['class' => 'text-primary-600'])
                                ->relationship('permissions', 'permission_name')
                                ->columns(4)
                                ->bulkToggleable()
                        ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('role_name')
                    ->label('Role name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('role_value')
                    ->label('Role value')
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make()->hidden(fn ($record) => $record->role_name === 'Superadmin')
                    ->tooltip(fn (Model $record): string => "Edit $record->role_name"),
                Tables\Actions\DeleteAction::make()->hidden(fn ($record) => $record->role_name === 'Superadmin')
                    ->tooltip(fn (Model $record): string => "Delete $record->role_name")
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),

            ]);
    }

    public static function getRelations(): array
    {
        return [
            PermissionsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRoles::route('/create'),
            'edit' => Pages\EditRoles::route('/{record}/edit'),
        ];
    }
}

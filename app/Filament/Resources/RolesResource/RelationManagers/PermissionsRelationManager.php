<?php

namespace App\Filament\Resources\RolesResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Tables\Contracts\HasTable;
use stdClass;

class PermissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'permissions';

    protected static ?string $recordTitleAttribute = 'permission_name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('permission_name')
                        // ->required()
                        ->maxLength(255),
                        Forms\Components\TextInput::make('permission_value')
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('index')->getStateUsing(
                //     static function (stdClass $rowLoop, HasTable $livewire): string {
                //         return (string) (
                //             $rowLoop->iteration +
                //             ($livewire->tableRecordsPerPage * (
                //                 $livewire->page - 1
                //             ))
                //         );
                //     }
                // ),
                Tables\Columns\TextColumn::make('permission_name'),
                Tables\Columns\TextColumn::make('permission_value')
            ])
            ->filters([
                //
            ]);
            // ->headerActions([
            //     Tables\Actions\CreateAction::make(),
            // ])
            // ->actions([
            //     Tables\Actions\EditAction::make(),
            //     Tables\Actions\DeleteAction::make(),
            // ]);
            // ->bulkActions([
            //     Tables\Actions\DeleteBulkAction::make(),
            // ]);
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LabelResource\Pages;
use App\Filament\Resources\LabelResource\RelationManagers;
use App\Helpers\RoleHelpers;
use App\Models\Label;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LabelResource extends Resource
{
    protected static ?string $model = Label::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        $labelTypes = Label::distinct()->pluck('type')->toArray();
        $formatedLabelTypes = array_combine($labelTypes, $labelTypes);
        $formatedLabelTypes['create_new'] = "Create a new type";

        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('value')
                            ->label('Label value')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('description')
                            ->label('Label description')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Section::make("Label type")
                            ->description('Select an existing type or create a new one')
                        ->schema([
                        Forms\Components\Select::make('type')
                            ->required()
                            ->reactive()
                            ->options($formatedLabelTypes),
                        Forms\Components\TextInput::make('new_type')
                            ->label('Enter a new type')
                            ->hidden(fn(\Filament\Forms\Get $get) => $get('type') !== 'create_new' )
                            ->required(fn(\Filament\Forms\Get $get) => $get('type') === 'create_new' ),
                    ])

            ])
            ]);
    }

    public static function table(Table $table): Table
    {
        $labelTypes = Label::distinct()->pluck('type')->toArray();
        $labelTypesAssoc = array_combine($labelTypes, $labelTypes);

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('value')
                    ->limit(20),
                Tables\Columns\TextColumn::make('description')
                    ->limit(20),
                Tables\Columns\TextColumn::make('type')
                    ->limit(20),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options($labelTypesAssoc)
                    ->label('Label type')
                    ->attribute('type'),
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
            'index' => Pages\ListLabels::route('/'),
            'create' => Pages\CreateLabel::route('/create'),
            'edit' => Pages\EditLabel::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return RoleHelpers::has_minimum_privilege('admin');
    }

}

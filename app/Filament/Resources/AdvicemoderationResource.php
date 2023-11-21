<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdvicemoderationResource\Pages;
use App\Filament\Resources\AdvicemoderationResource\RelationManagers;
use App\Helpers\RoleHelpers;
use App\Models\Advice;
use App\Models\Advicemoderation;
use App\Models\Advicetype;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class AdvicemoderationResource extends Resource
{
    protected static ?string $model = Advicemoderation::class;
    protected static ?string $modelLabel = 'Advice Moderation';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Moderation';
    protected static ?string $navigationLabel = 'Advice Moderation';

    protected static ?int $navigationSort = 1;


    public static function form(Form $form): Form
    {

        return $form->schema([
            Forms\Components\Card::make()->schema([
                Forms\Components\TextInput::make('post_title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('post_status')
                    ->options([
                        'Published' => 'Published',
                        'Unpublished' => 'Unpublished',
                        'Draft' => 'Draft',
                        'Pending' => 'Pending'
                    ])
                    ->label('Status')
                    ->required(),
            ]),
        ]);
    }




    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('post_title')
                    ->label('Title')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('advicetype.advice_type_name')
                    ->label('Type')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('author.full_name')->label('Author'),
                Tables\Columns\TextColumn::make('post_date')
                    ->date()
                    ->label('Created At'),
                Tables\Columns\TextColumn::make('post_modified')
                    ->date()
                    ->label('Modified At'),
                Tables\Columns\TextColumn::make('post_status')
                    ->label('Status')
                    ->sortable()
                    ->searchable(),
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
            'index' => Pages\ListAdvicemoderations::route('/'),
            'create' => Pages\CreateAdvicemoderation::route('/create'),
            'edit' => Pages\EditAdvicemoderation::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('post_status', 'Pending');
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::query()->where('post_status', 'pending')->count();
        if ($count > 0) {
            return $count;
        } else {
            return '';
        }
    }

    public static function shouldRegisterNavigation(): bool
    {
        return RoleHelpers::has_minimum_privilege('site_leader');
    }

}

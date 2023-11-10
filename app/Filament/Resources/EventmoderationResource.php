<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventmoderationResource\Pages;
use App\Filament\Resources\EventmoderationResource\RelationManagers;
use App\Helpers\RoleHelpers;
use App\Models\Eventmoderation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;


class EventmoderationResource extends Resource
{
    protected static ?string $model = Eventmoderation::class;
    protected static ?string $modelLabel= "Event Moderation";

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Moderation';
    protected static ?string $navigationLabel = 'Event Moderation';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        $user = Auth::user()->full_name;
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                Forms\Components\TextInput::make('event_title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\RichEditor::make('event_content')
                    ->required(),
                Forms\Components\RichEditor::make('event_excerpt')
                    ->disableToolbarButtons([
                        'attachFiles',
                    ])
                    ->maxLength(65535),
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\DateTimePicker::make('start_date')
                            ->required(),
                        Forms\Components\DateTimePicker::make('end_date')
                            ->required(),
                        // Forms\Components\TextInput::make('Author')
                        //     ->default($user)
                        //     ->disabled(),
                        ]),
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\BelongsToSelect::make('event_type')
                            ->label('Event type')
                            ->relationship('eventtype', 'event_type_name'),
                        Forms\Components\Select::make('event_status')
                            ->options([
                                'Published' => 'Published',
                                'Unpublished' => 'Unpublished',
                                'Draft' => 'Draft',
                                'Pending' => 'Pending'
                            ])
                            ->required(),
                        ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event_title')
                    ->label('Title')
                    ->limit(20)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('event_content')
                    ->label('Content')
                    ->limit(20),
                Tables\Columns\TextColumn::make('event_status')
                    ->label('status')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListEventmoderations::route('/'),
            'create' => Pages\CreateEventmoderation::route('/create'),
            'edit' => Pages\EditEventmoderation::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('event_status', 'Pending');
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::query()->where('event_status', 'pending')->count();
        if ($count > 0){
            return $count;
        }else{
            return '';
        }
    }
    public static function shouldRegisterNavigation(): bool
    {
        return RoleHelpers::has_minimum_privilege('site_leader');
    }

}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SoftwaremoderationResource\Pages;
use App\Filament\Resources\SoftwaremoderationResource\RelationManagers;
use App\Models\Softwaremoderation;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class SoftwaremoderationResource extends Resource
{
    protected static ?string $model = Softwaremoderation::class;
    protected static ?string $modelLabel= "Software Moderation";


    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Moderation';
    protected static ?string $navigationLabel = 'Software Moderation';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        $user = Auth::user()->full_name;
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('post_title')
                            ->label('Title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\RichEditor::make('post_content')
                            ->label('Content')
                            ->required(),
                        Forms\Components\RichEditor::make('post_excerpt')
                            ->label('Excerpt')
                            ->disableToolbarButtons([
                                'attachFiles',
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                // Forms\Components\TextInput::make('Author')
                                //     ->default($user)
                                //     ->disabled(),
                                Forms\Components\BelongsToSelect::make('software_type')
                                    ->label('Software type')
                                    ->relationship('softwaretype', 'software_type_name'),
                                Forms\Components\Select::make('post_status')
                                    ->options([
                                        'Published' => 'Published',
                                        'Unpublished' => 'Unpublished',
                                        'Draft' => 'Draft',
                                        'Pending' => 'Pending'
                                    ])
                                    ->label('Status')
                                    ->required()
                            ]),
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
                Tables\Columns\TextColumn::make('softwaretype.software_type_name')
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
        return parent::getEloquentQuery()->where('post_status', 'Pending');
    }

    protected static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::query()->where('post_status', 'pending')->count();
        if ($count > 0){
            return $count;
        }else{
            return '';
        }
    }

    public static function shouldRegisterNavigation(): bool
    {
        $allowed_array = ['Superadmin', 'Administrator','Moderator'];
        if (in_array(Auth::user()->role->role_name, $allowed_array)) {
            return true;
        }
        return false;
    }
}

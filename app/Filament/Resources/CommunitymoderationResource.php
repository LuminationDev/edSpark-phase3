<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommunitymoderationResource\Pages;
use App\Filament\Resources\CommunitymoderationResource\RelationManagers;
use App\Helpers\RoleHelpers;
use App\Helpers\UserRole;
use App\Models\Communitymoderation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Illuminate\Support\Facades\Auth;


class CommunitymoderationResource extends Resource
{
    protected static ?string $model = Communitymoderation::class;
    protected static ?string $modelLabel = 'Community Moderation';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Moderation';
    protected static ?string $navigationLabel = 'Community Moderation';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        $user = Auth::user()->full_name;
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('post_title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\RichEditor::make('post_content')
                            ->required(),
                        Forms\Components\RichEditor::make('post_excerpt')
                            ->maxLength(65535)
                            ->disableToolbarButtons([
                                'attachFiles',
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                // Forms\Components\TextInput::make('Author')
                                //     ->default($user)
                                //     ->disabled(),
                                Forms\Components\BelongsToSelect::make('community_type')
                                    ->label('Community type')
                                    ->relationship('communitytype', 'community_type_name'),
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
                                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('post_title')
                    ->label('Title')
                    ->limit(20)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('post_content')
                    ->label('Content')
                    ->limit(50),
                Tables\Columns\TextColumn::make('author.full_name')->label('Author'),
                Tables\Columns\TextColumn::make('post_status')
                    ->label('Status')
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
            'index' => Pages\ListCommunitymoderations::route('/'),
            'create' => Pages\CreateCommunitymoderation::route('/create'),
            'edit' => Pages\EditCommunitymoderation::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('post_status', 'Pending');
    }

    public static function getNavigationBadge(): ?string
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
        return RoleHelpers::has_minimum_privilege(UserRole::GODMODE);

    }
}

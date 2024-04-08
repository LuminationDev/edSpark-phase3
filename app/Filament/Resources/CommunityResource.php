<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommunityResource\Pages;
use App\Filament\Resources\CommunityResource\RelationManagers;
use App\Helpers\RoleHelpers;
use App\Helpers\UserRole;
use App\Models\Community;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;

use Illuminate\Support\Facades\Auth;
use Livewire\TemporaryUploadedFile;


class CommunityResource extends Resource
{
    protected static ?string $model = Community::class;
    protected static ?string $modelLabel = 'Community';


    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationGroupIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        $user = Auth::user()->full_name;
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\RichEditor::make('content')
                            ->required()
                            ->disableToolbarButtons([
                                'attachFiles',
                            ]),
                        Forms\Components\RichEditor::make('excerpt')
                            ->maxLength(65535),
                        Forms\Components\FileUpload::make('cover_image')
                            ->preserveFilenames()
                            ->disk('public')
                            ->directory('uploads/community')
                            ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png'])
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                return (string) str($file->getClientOriginalName())->prepend('edSpark-community-');
                            }),
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('Author')
                                    ->default($user)
                                    ->disabled(),
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
                // Tables\Columns\TextColumn::make('author_id'),
                // Tables\Columns\TextColumn::make('communitytype_id'),
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->limit(20)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('content')
                    ->label('Content')
                    ->limit(50),
                Tables\Columns\ImageColumn::make('cover_image'),
                Tables\Columns\TextColumn::make('author.full_name')->label('Author'),
                Tables\Columns\TextColumn::make('post_status')
                    ->label('Status')
                    ->sortable()
                    ->searchable(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCommunities::route('/'),
            'create' => Pages\CreateCommunity::route('/create'),
            'edit' => Pages\EditCommunity::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return RoleHelpers::has_minimum_privilege(UserRole::GODMODE);
    }



}

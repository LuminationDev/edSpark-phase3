<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SoftwareResource\Pages;
use App\Filament\Resources\SoftwareResource\RelationManagers;
use App\Models\Software;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Livewire\TemporaryUploadedFile;


class SoftwareResource extends Resource
{
    protected static ?string $model = Software::class;

    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationGroupIcon = 'heroicon-o-collection';

    protected static ?int $navigationSort = 3;


    protected static ?string $navigationIcon = 'heroicon-o-collection';

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
                        Forms\Components\FileUpload::make('cover_image')
                            ->preserveFilenames()
                            ->disk('public')
                            ->directory('uploads/software')
                            ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png'])
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                return (string) str($file->getClientOriginalName())->prepend('edSpark-software-');
                            }),
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('Author')
                                    ->default($user)
                                    ->disabled(),
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
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('post_content')
                ->limit(50)
                ->label('Content'),
                Tables\Columns\ImageColumn::make('cover_image'),
                Tables\Columns\TextColumn::make('author.full_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('softwaretype.software_type_name')
                    ->label('Type')
                    ->sortable()
                    ->searchable(),
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
            'index' => Pages\ListSoftware::route('/'),
            'create' => Pages\CreateSoftware::route('/create'),
            'edit' => Pages\EditSoftware::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        // use Illuminate\Support\Facades\Auth;

        // Moderator check
        if(Auth::user()->role->role_name == 'Moderator') {
            return false;
        }

        return true;
    }


}

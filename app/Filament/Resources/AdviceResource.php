<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdviceResource\Pages;
use App\Filament\Resources\AdviceResource\RelationManagers;
use App\Models\Advice;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Livewire\TemporaryUploadedFile;

class AdviceResource extends Resource
{
    protected static ?string $model = Advice::class;

    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationGroupIcon = 'heroicon-o-collection';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-light-bulb';

    // protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        $user = Auth::user()->full_name;
        return $form->schema([
            Forms\Components\Card::make()->schema([
                Forms\Components\TextInput::make('post_title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\RichEditor::make('post_content')
                    ->label('Content')
                    ->required()
                    ->maxLength(65535),
                Forms\Components\RichEditor::make('post_excerpt')
                    ->label('Excerpt')
                    ->disableToolbarButtons(['attachFiles']),
                Forms\Components\FileUpload::make('cover_image')
                    ->preserveFilenames()
                    ->disk('public')
                    ->directory('uploads/advice')
                    ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png'])
                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                        return (string) str($file->getClientOriginalName())->prepend('edSpark-advice-');
                    }),
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\TextInput::make('Author')
                        ->default($user)
                        ->disabled(),
                    Forms\Components\BelongsToSelect::make('advice_type')
                        ->label('Advice type')
                        ->relationship('advicetype', 'advice_type_name'),
                    Forms\Components\Select::make('post_status')
                        ->options([
                            'Published' => 'Published',
                            'Unpublished' => 'Unpublished',
                            'Draft' => 'Draft',
                            'Pending' => 'Pending',
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
                    ->sortable()
                    ->searchable(),
                Tables\Columns\ImageColumn::make('cover_image'),
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
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
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
            'index' => Pages\ListAdvice::route('/'),
            'create' => Pages\CreateAdvice::route('/create'),
            'edit' => Pages\EditAdvice::route('/{record}/edit'),
        ];
    }

    // public static function getEloquentQuery(): Builder
    // {
    //     return parent::getEloquentQuery()->where('post_status', 'Published');
    // }
}

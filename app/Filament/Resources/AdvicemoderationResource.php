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
        $adviceAuthor= Advicemoderation::find($form->model->id)->author;
        $authorName = $adviceAuthor->full_name;

        $advicemoderation = Advice::find($form->model->id);
        $adviceTypeIds = $advicemoderation->advicetypes->pluck('id')->toArray();
        $adviceTypes = Advicetype::find($adviceTypeIds[0]);
//        dd($adviceTypes->advice_type_name);

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

                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('advice_type_display')
                        ->label('Advice type')
                        ->columns(3)
                        ->default($adviceTypes->advice_type_name)
                        ->disabled()
                    ,

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

                Forms\Components\TextInput::make('author_id')
                    ->label('Author')
                    ->default($authorName) // Set the author's name as the default value
                    ->disabled(),

                Forms\Components\TagsInput::make('tags')
                    ->placeholder('Add or create tags')
                    ->helperText('Press enter after each tag'),
            ]),
        ]);
    }


//    public static function form(Form $form): Form
//    {
//        $user = Auth::user()->full_name;
//        return $form
//            ->schema([
//                Forms\Components\Card::make()
//                    ->schema([
//                        Forms\Components\TextInput::make('post_title')
//                            ->label('Title')
//                            ->required()
//                            ->maxLength(255),
//                        Forms\Components\RichEditor::make('post_content')
//                            ->label('Content')
//                            ->required()
//                            ->maxLength(65535),
//                        Forms\Components\RichEditor::make('post_excerpt')
//                            ->label('Excerpt')
//                            ->disableToolbarButtons([
//                                'attachFiles',
//                            ]),
//                        Forms\Components\Grid::make(2)
//                            ->schema([
//                                // Forms\Components\TextInput::make('author_id')
//                                //     ->label('Author')
//                                //     ->relationship('author', 'full_name')
//                                //     ->disabled(),
//                                Forms\Components\BelongsToSelect::make('advice_type')
//                                    ->label('Advice type')
//                                    ->relationship('advicetype', 'advice_type_name'),
//                                Forms\Components\Select::make('post_status')
//                                    ->options([
//                                        'Published' => 'Published',
//                                        'Unpublished' => 'Unpublished',
//                                        'Draft' => 'Draft',
//                                        'Pending' => 'Pending'
//                                    ])
//                                    ->label('Status')
//                                    ->required(),
//                            ]),
//                    ]),
//            ]);
//    }

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

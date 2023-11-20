<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SoftwareResource\Pages;
use App\Filament\Resources\SoftwareResource\RelationManagers;
use App\Helpers\RoleHelpers;
use App\Models\Software;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;


use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use SplFileInfo;


class SoftwareResource extends Resource
{
    protected static ?string $model = Software::class;
    protected static ?string $modelLabel = "Software";

    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationGroupIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                                return (string)str($file->getClientOriginalName())->prepend('edSpark-software-');
                            }),

                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\CheckboxList::make('software_type')
                                    ->label('Software type')
                                    ->extraAttributes(['class' => 'text-primary-600'])
                                    ->relationship('softwaretypes', 'software_type_name')
                                    ->columns(3)
                                    ->bulkToggleable()
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('Author')
                                    ->default($user)
                                    ->disabled(),
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
                        Forms\Components\TagsInput::make('tags')
                            ->placeholder('Add or create tags')
                            ->helperText('Press enter after each tag')
                    ]),

                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Builder::make('extra_content')
                            ->blocks([
                                    Forms\Components\Builder\Block::make('templates')
                                        ->schema([
                                            Forms\Components\Select::make('template')
                                                ->label('Choose a Template')
                                                ->reactive()
                                                ->options(static::getTemplates()),
                                            ...static::getTemplateSchemas()
                                        ]),
                                ]
                            )
                            ->label('Extra content')

                    ])

            ]);
    }

    public static function getTemplates(): Collection
    {
        return static::getTemplateClasses()->mapWithKeys(fn($class) => [$class => $class::title()]);
    }

    public static function getTemplateClasses(): Collection
    {
        $filesystem = app(Filesystem::class);

        return collect($filesystem->allFiles(app_path('Filament/PageTemplates')))
            ->map(function (SplFileInfo $file): string {
                return (string)Str::of('App\\Filament\\PageTemplates')
                    ->append('\\', $file->getRelativePathname())
                    ->replace(['/', '.php'], ['\\', '']);
            });
    }

    public static function getTemplateSchemas(): array
    {
        return static::getTemplateClasses()
            ->map(fn($class) => Forms\Components\Group::make($class::schema())
                ->columnSpan(2)
                ->afterStateHydrated(fn($component, $state) => $component->getChildComponentContainer()->fill($state))
                ->statePath('extra_content.' . static::getTemplateName($class))
                ->visible(fn($get) => $get('template') == $class)
            )
            ->toArray();

    }


    public static function getTemplateName($class)
    {
        return Str::of($class)->afterLast('\\')->snake()->toString();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('post_title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),
//                Tables\Columns\TextColumn::make('post_content')
//                ->limit(50)
//                ->label('Content'),
                Tables\Columns\ImageColumn::make('cover_image'),
                Tables\Columns\TextColumn::make('softwaretypes.software_type_name')
                    ->label('Type')
                    ->sortable()
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('author.full_name')
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderBy('created_at', 'DESC'); // TODO: Change the autogenerated stub
    }

    public static function shouldRegisterNavigation(): bool
    {
        return RoleHelpers::has_minimum_privilege('site_leader');
    }

}

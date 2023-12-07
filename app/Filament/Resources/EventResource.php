<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Helpers\RoleHelpers;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Illuminate\Support\Facades\Auth;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;


use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\Tags\Tag;
use SplFileInfo;

use Closure;


class EventResource extends Resource
{
    protected static ?string $model = Event::class;
    protected static ?string $modelLabel= "Event";

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationGroupIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 5;

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
                        Forms\Components\TextInput::make('event_excerpt')
                            ->label('Tagline')
                            ->placeholder('150 characters or less')
                            ->maxLength(150),
                        Forms\Components\RichEditor::make('event_content')
                            ->required()
                            ->maxLength(65535),
                        Forms\Components\FileUpload::make('cover_image')
                            ->preserveFilenames()
                            ->disk('public')
                            ->directory('uploads/event')
                            ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png'])
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                return (string) str($file->getClientOriginalName())->prepend('edSpark-event-');
                            }),
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\DateTimePicker::make('start_date')
                                    ->required(),
                                Forms\Components\DateTimePicker::make('end_date')
                                    ->required(),
                                Forms\Components\TextInput::make('Author')
                                    ->default($user)
                                    ->disabled(),
                            ]),
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\BelongsToSelect::make('event_type')
                                    ->label('Event type')
                                    ->reactive()
                                    ->relationship('eventtype', 'event_type_name'),
                                Forms\Components\TextInput::make('url')
                                    ->label('URL')
                                    ->hidden(fn (\Filament\Forms\Get $get) => $get('event_type') === null || $get('event_type') == '7'),
                                Forms\Components\TextInput::make('address')
                                    ->label('Address')
                                    ->hidden(fn (\Filament\Forms\Get $get) => $get('event_type') === null || $get('event_type') == '6'),                                ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('event_status')
                                    ->options([
                                        'Published' => 'Published',
                                        'Unpublished' => 'Unpublished',
                                        'Draft' => 'Draft',
                                        'Pending' => 'Pending'
                                    ])
                                    ->required(),
                            ]),
                        Forms\Components\TagsInput::make('tags')
                            ->placeholder('Add or create tags')
                            ->helperText('Press enter after each tag'),
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
                                            ])
                                    ])
                            ])

                    ])

            ]);
    }

    public static function getTemplates(): Collection
    {
        return static::getTemplateClasses()->mapWithKeys(fn ($class) => [$class => $class::title()]);
    }

    public static function getTemplateClasses(): Collection
    {
        $filesystem = app(Filesystem::class);

        return collect($filesystem->allFiles(app_path('Filament/PageTemplates')))
            ->map(function (SplFileInfo $file): string {
                return (string) Str::of('App\\Filament\\PageTemplates')
                    ->append('\\', $file->getRelativePathname())
                    ->replace(['/', '.php'],['\\', '']);
            });
    }

    public static function getTemplateSchemas(): array
    {
        return static::getTemplateClasses()
            ->map(fn ($class) =>
            Forms\Components\Group::make($class::schema())
                ->columnSpan(2)
                ->afterStateHydrated(fn ($component, $state) => $component->getChildComponentContainer()->fill($state))
                ->statePath('extra_content.' . static::getTemplateName($class))
                // ->statePath(static::getTemplateName($class))
                ->visible(fn ($get) => $get('template') == $class)
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
                Tables\Columns\TextColumn::make('event_title')
                    ->label('Title')
                    ->limit(20)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('event_content')
                    ->label('Content')
                    ->limit(20),
                Tables\Columns\ImageColumn::make('cover_image'),
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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return RoleHelpers::has_minimum_privilege('site_leader');
    }

}

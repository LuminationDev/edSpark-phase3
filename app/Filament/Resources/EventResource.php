<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Helpers\CustomHtmlable;
use App\Helpers\EdSparkFormComponents;
use App\Helpers\RoleHelpers;
use App\Helpers\StatusHelpers;
use App\Helpers\UserRole;
use App\Models\Event;
use App\Models\Eventformat;
use App\Models\Label;
use Coolsam\FilamentFlatpickr\Forms\Components\Flatpickr;
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
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;
use SplFileInfo;

use Closure;


class EventResource extends Resource
{
    protected static ?string $model = Event::class;
    protected static ?string $modelLabel = "Event";

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationGroupIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        $current_user = Auth::user();

        $groupedLabels = Label::all()->groupBy('type');
        $labelColumns = [];
        $categoriesToInclude = ['category', 'learning', 'capability', 'year', 'partnerships'];
        foreach ($groupedLabels as $category => $labels) {
            if (in_array($category, $categoriesToInclude)) {
                $labelColumns[] =
                    Forms\Components\Section::make(ucfirst($category))
                        ->schema([
                            Forms\Components\CheckboxList::make("labels")
                                ->label("")
                                ->extraAttributes(['class' => 'text-primary-600'])
                                ->options($labels->pluck('value', 'id')->toArray())
                                ->relationship('labels', 'value', function ($query) use ($category) {
                                    $query->where('type', $category);
                                })
                                ->columns(3)
                        ]);
            }
        }

        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('excerpt')
                            ->label('Tagline')
                            ->placeholder('150 characters or less')
                            ->maxLength(150),
                        TinyEditor::make('content')
                            ->label('Content')->fileAttachmentsDisk('local')
                            ->fileAttachmentsVisibility('public')
                            ->fileAttachmentsDirectory('public/uploads/event')
                            ->required(),
                        EdSparkFormComponents::createFileUploadComponent('uploads/event', 'edSpark-event-' ),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Flatpickr::make('start_date')
                                    ->enableTime()
                                    ->required(),
                                Flatpickr::make('end_date')
                                    ->enableTime()
                                    ->required(),
                            ]),
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('event_format')
                                    ->label('Event format')
                                    ->required()
                                    ->reactive()
                                    ->relationship('event_format', 'event_format_name'),
                                Forms\Components\TextInput::make('url')
                                    ->label('URL')
                                    ->hidden(function (\Filament\Forms\Get $get) {
                                        $eventFormatId = $get('event_format');
                                        if ($eventFormatId !== null && Eventformat::find($eventFormatId)) {
                                            $eventFormatName = Eventformat::find($eventFormatId)->event_format_name;
                                            return $eventFormatName === 'In Person';
                                        }
                                        return true;
                                    }),
                                Forms\Components\TextInput::make('address')
                                    ->label('Address')
                                    ->hidden(function (\Filament\Forms\Get $get) {
                                        $eventFormatId = $get('event_format');
                                        if ($eventFormatId !== null && Eventformat::find($eventFormatId)) {
                                            $eventFormatName = Eventformat::find($eventFormatId)->event_format_name;
                                            return $eventFormatName === 'Virtual';
                                        }
                                        return true;
                                    }),
                                Forms\Components\BelongsToSelect::make('event_type')
                                    ->label('Event type')
                                    ->required()
                                    ->relationship('event_type', 'event_type_name'),
                            ]),
                        Forms\Components\Section::make()
                            ->schema([
                                ...$labelColumns
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('author_id')
                                    ->relationship(name: 'author', titleAttribute: 'display_name')
                                    ->disabled(fn() => !RoleHelpers::has_minimum_privilege(UserRole::ADMIN))
                                    ->required()
                                    ->searchable(),
                                Forms\Components\Select::make('status')
                                    ->options(StatusHelpers::getStatusList())
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
                                                    ->label('Choose a template')
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
                // ->statePath(static::getTemplateName($class))
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
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->sortable()
                    ->limit(30)
                    ->searchable(),

                Tables\Columns\ImageColumn::make('cover_image')
                    ->square()
                    ->getStateUsing(function ($record): string {
                        $imgPath = $record->cover_image;
                        if ($imgPath) {
                            return env('AZURE_STORAGE_ENDPOINT') . env('AZURE_STORAGE_CONTAINER') .'/'. $imgPath;
                        } else {
                            return '';
                        }
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('j M y, h:i a'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(StatusHelpers::getStatusList())
                    ->label('Event status')
                    ->default('published')
                    ->attribute('status'),
                Tables\Filters\SelectFilter::make('event_date')
                    ->options([
                        'all' => 'All Events',
                        'past' => 'Past Events',
                        'upcoming' => 'Upcoming Events',
                    ])
                    ->label('Event Date')
                    ->default('upcoming')
                    ->attribute('start_date')
                    ->query(function (Builder $query, array $data): Builder {
                        $today = now()->toDateString();
                        switch ($data['value']) {
                            case 'past':
                                return $query->where('end_date', '<', $today);
                            case 'upcoming':
                                return $query->where('end_date', '>=', $today);
                            default:
                                return $query;
                        }
                    })
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

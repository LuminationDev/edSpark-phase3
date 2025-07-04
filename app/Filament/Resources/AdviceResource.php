<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdviceResource\Pages;
use App\Helpers\EdsparkFormComponents;
use App\Helpers\RoleHelpers;
use App\Helpers\StatusHelpers;
use App\Helpers\UserRole;
use App\Models\Advice;
use App\Models\Label;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;


use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use SplFileInfo;


class AdviceResource extends Resource
{
    protected static ?string $model = Advice::class;
    protected static ?string $modelLabel = 'Advice';

    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationGroupIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-light-bulb';

    public static function form(Form $form): Form
    {
        $groupedLabels = Label::all()->groupBy('type');

        $labelColumns = [];
        // categories to include inside advice creation page
        $categoriesToInclude = ['category', 'learning', 'capability', 'year'];

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
        return $form->schema([
            Forms\Components\Card::make()->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('excerpt')
                    ->label('Tagline')
                    ->placeholder('150 characters or less')
                    ->maxLength(150),
                EdsparkFormComponents::createContentComponent('uploads/content/advice'),
                EdsparkFormComponents::createCoverImageComponent('uploads/advice', 'edSpark-advice-'),
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('advice_type')
                            ->label('Advice type')
                            ->relationship('advice_types', 'advice_type_name')
                            ->required()
                            ->columns(3),
                        ...$labelColumns
                    ]),

                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\Select::make('author_id')
                        ->relationship(name: 'author', titleAttribute: 'display_name')
                        ->disabled(fn() => !RoleHelpers::has_minimum_privilege(UserRole::MODERATOR))
                        ->required()
                        ->searchable(),
                    Forms\Components\Select::make('status')
                        ->options(StatusHelpers::getStatusList())
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
                                        ->label('Choose a template')
                                        ->reactive()
                                        ->options(static::getTemplates()),
                                    ...static::getTemplateSchemas()
                                ]),

                        ])
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
                    ->limit(25)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\ImageColumn::make('cover_image')
                    ->square()
                    ->getStateUsing(function ($record): string {
                        $imgPath = $record->cover_image;
                        if ($imgPath) {
                            return env('AZURE_STORAGE_ENDPOINT') . env('AZURE_STORAGE_CONTAINER') . '/' . stripslashes($imgPath);
                        } else {
                            return '';
                        }
                    }),
                Tables\Columns\TextColumn::make('advice_types.advice_type_name')
                    ->sortable()
                    ->label('Type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('author.display_name')->label('Author')
                    ->searchable()
                    ->limit(15)
                ,
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->date()
                    ->label('Last modified')
                    ->sortable()
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(StatusHelpers::getStatusList())
                    ->label('Guide status')
                    ->default('published')
                    ->attribute('status'),
            ])
            ->actions([
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

    public static function getEloquentQuery(): Builder
    {
        return Advice::whereDoesntHave('advice_types', function ($query) {
            $query->whereIn('advice_type_name', ['Learning Task','DAG']);
        });
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdvice::route('/'),
            'create' => Pages\CreateAdvice::route('/create'),
            'edit' => Pages\EditAdvice::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return RoleHelpers::has_minimum_privilege('site_leader');
    }
}

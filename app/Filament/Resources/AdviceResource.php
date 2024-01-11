<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdviceResource\Pages;
use App\Filament\Resources\AdviceResource\RelationManagers;
use App\Helpers\RoleHelpers;
use App\Models\Advice;
use App\Models\Label;
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

use App\Helpers\EdSparkRolesHelpers;

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
        $user = Auth::user()->full_name;
        $groupedLabels = Label::all()->groupBy('type');

        $labelColumns = [];

        foreach ($groupedLabels as $category => $labels) {
            $labelColumns[] = Forms\Components\CheckboxList::make("labels")
                ->label("Labels - {$category}")
                ->extraAttributes(['class' => 'text-primary-600'])
                ->options($labels->pluck('value', 'id')->toArray())
                ->relationship('labels', 'value', function ($query) use ($category) {
                    $query->where('type', $category)->orderByRaw('CAST(labels.id AS SIGNED)');
                })
                ->columns(3);
        }
        return $form->schema([
            Forms\Components\Card::make()->schema([
                Forms\Components\TextInput::make('post_title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('post_excerpt')
                    ->label('Tagline')
                    ->placeholder('150 characters or less')
                    ->maxLength(150),
                TinyEditor::make('post_content')
                    ->label('Content')->fileAttachmentsDisk('local')
                    ->fileAttachmentsVisibility('public')
                    ->fileAttachmentsDirectory('public/uploads/advice')
                    ->required(),

                Forms\Components\FileUpload::make('cover_image')
                    ->preserveFilenames()
                    ->disk('public')
                    ->directory('uploads/advice')
                    ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png'])
                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                        return (string)str($file->getClientOriginalName())->prepend('edSpark-advice-');
                    }),

                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\CheckboxList::make('advice_type')
                            ->label('Advice type')
                            ->extraAttributes(['class' => 'text-primary-600'])
                            ->relationship('advicetypes', 'advice_type_name')
                            ->columns(3),
                        ...$labelColumns
//                        Forms\Components\CheckboxList::make('labels')
//                            ->label('Labels')
//                            ->extraAttributes(['class' => 'text-primary-600'])
//                            ->relationship('labels', 'value')
//                            ->columns(3)
//                            ->bulkToggleable(),
                    ]),

                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('Author')
                        ->default($user)
                        ->disabled(),
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
                Tables\Columns\TextColumn::make('post_title')
                    ->label('Title')
                    ->limit(30)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\ImageColumn::make('cover_image'),
                Tables\Columns\TextColumn::make('advicetypes.advice_type_name')
                    ->label('Type')
                    ->sortable()
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('author.full_name')->label('Author'),
                Tables\Columns\TextColumn::make('post_status')
                    ->label('Status')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('post_modified')
                    ->date()
                    ->label('Modified At'),
                Tables\Columns\TextColumn::make('post_date')
                    ->date()
                    ->label('Created At'),
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

    public static function getEloquentQuery(): Builder
    {
        // return parent::getEloquentQuery()->where('post_status', 'Published');
        return parent::getEloquentQuery()->orderBy('created_at', 'DESC');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return RoleHelpers::has_minimum_privilege('site_leader');
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HardwareResource\Pages;
use App\Filament\Resources\HardwareResource\RelationManagers;
use App\Models\Hardware;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;


use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\TemporaryUploadedFile;
use Illuminate\Support\Facades\Auth;
use SplFileInfo;


class HardwareResource extends Resource
{
    protected static ?string $model = Hardware::class;
    protected static ?string $modelLabel= "Hardware";


    protected static ?string $navigationGroup = 'Product Management';
    protected static ?string $navigationGroupIcon = 'heroicon-o-collection';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('product_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\RichEditor::make('product_content')
                            ->required(),
                        Forms\Components\RichEditor::make('product_excerpt')
                            ->maxLength(65535)
                            ->disableToolbarButtons([
                                'attachFiles'
                            ]),
                        Forms\Components\FileUpload::make('cover_image')
                            ->preserveFilenames()
                            ->disk('public')
                            ->directory('uploads/hardware')
                            ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png'])
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                return (string)str($file->getClientOriginalName())->prepend('edSpark-hardware-');
                            }),
                        Forms\Components\FileUpload::make('gallery')
                            ->multiple()
                            ->preserveFilenames()
                            ->disk('public')
                            ->directory('uploads/hardware/gallery')
                            ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png'])
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                return (string)str($file->getClientOriginalName())->prepend('edSpark-hardware-gallery-');
                            })
                            ->enableReordering()
                            ->minFiles(2)
                            ->maxFiles(5),
                        Forms\Components\TextInput::make('price')
                            ->required(),
                        Forms\Components\BelongsToSelect::make('brand')
                            ->relationship('brand', 'product_brand_name'),
                        Forms\Components\BelongsToSelect::make('category')
                            ->relationship('category', 'product_category_name'),
                        Forms\Components\TagsInput::make('tags')
                            ->placeholder('Add or create tags')
                            ->helperText('Separate tags with commas')
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
                                Forms\Components\Builder\Block::make('extra_resources')
                                    ->schema([
                                        Forms\Components\Repeater::make('item')
                                            ->schema([
                                                Forms\Components\TextInput::make('heading'),
                                                Forms\Components\RichEditor::make('content')
                                                    ->disableToolbarButtons([
                                                        'attachFiles',
                                                        'blockquote',
                                                        'bulletList',
                                                        'codeBlock',
                                                        'h2',
                                                        'h3',
                                                        'orderedList',
                                                        'redo',
                                                        'undo',
                                                    ]),
                                            ])
                                    ])
                                    ->label('Extra Resources')
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

        return collect($filesystem->allFiles(app_path('Filament/PageTemplates/Hardware')))
            ->map(function (SplFileInfo $file): string {
                return (string)Str::of('App\\Filament\\PageTemplates\\Hardware')
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
                Tables\Columns\TextColumn::make('product_name')
                    ->label("Name")
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('brand.product_brand_name')
                    ->label('Brand')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.product_category_name')
                    ->label('Category')
                    ->sortable()
                    ->searchable(),
                // Tables\Columns\TextColumn::make('product_inventory'),
                Tables\Columns\TextColumn::make('price'),
                Tables\Columns\IconColumn::make('product_isLoan')
                    ->boolean()
                    ->label('IsLoan'),
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
            'index' => Pages\ListHardware::route('/'),
            'create' => Pages\CreateHardware::route('/create'),
            'edit' => Pages\EditHardware::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        $allowed_array = ['Superadmin', 'Administrator','Moderator'];
        if (in_array(Auth::user()->role->role_name, $allowed_array)) {
            return true;
        }
        return false;
    }
}

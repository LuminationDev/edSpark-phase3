<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartnerprofileResource\Pages;
use App\Filament\Resources\PartnerprofileResource\RelationManagers;
use App\Models\Partnerprofile;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\TemporaryUploadedFile;
use App\Models\User;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use SplFileInfo;

class PartnerprofileResource extends Resource
{
    protected static ?string $model = Partnerprofile::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = "User Management";
    protected static ?string $navigationLabel = "Partner Profile";
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\TextInput::make('introduction')
                        ->label('Introduction')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\RichEditor::make('motto')
                        ->label('Motto')
                        ->disableToolbarButtons(['attachFiles']),
                    Forms\Components\FileUpload::make('logo')
                        ->preserveFilenames()
                        ->disk('public')
                        ->directory('uploads/partner/logo')
                        ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png'])
                        ->getUploadedFileNameForStorageUsing( function (TemporaryUploadedFile $file): string {
                            return (string) str($file->getClientOriginalName())->prepend('edSpark-partner-logo-');
                        }),
                    Forms\Components\FileUpload::make('cover_image')
                        ->preserveFilenames()
                        ->disk('public')
                        ->directory('uploads/partner/coverimage')
                        ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png'])
                        ->getUploadedFileNameForStorageUsing( function (TemporaryUploadedFile $file): string {
                            return (string) str($file->getClientOriginalName())->prepend('edSpark-partner-');
                        }),
                ]),

                //Template
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Builder::make('content')
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


            ]);
    }

    public static function getTemplates(): Collection
    {
        return static::getTemplateClasses()->mapWithKeys( fn ($class) => [$class => $class::title()]);
    }

    public static function getTemplateClasses(): Collection
    {
        $filesystem = app(Filesystem::class);

        return collect($filesystem->allFiles(app_path('Filament/PageTemplates/Partner')))
            ->map(function (SplFileInfo $file): string {
                return (string) Str::of('App\\Filament\\PageTemplates\\Partner')
                    ->append('\\', $file->getRelativePathName())
                    ->replace(['/', '.php'], ['\\', '']);
            });
    }

    public static function getTemplateSchemas(): array
    {
        return static::getTemplateClasses()
            ->map(fn ($class) =>
                Forms\Components\Group::make($class::schema())
                    ->columnSpan(2)
                    ->afterStateHydrated(fn ($component, $state) => $component->getChildComponentContainer()->fill($state))
                    ->statePath('content.' . static::getTemplateName($class))
                    ->visible(fn ($get) => $get('template') == $class)
            )->toArray();
    }

    public static function getTemplateName($class)
    {
        return Str::of($class)->afterLast('\\')->snake()->toString();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
//                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPartnerprofiles::route('/'),
            'create' => Pages\CreatePartnerprofile::route('/create'),
            'edit' => Pages\EditPartnerprofile::route('/{record}/edit'),
        ];
    }
}

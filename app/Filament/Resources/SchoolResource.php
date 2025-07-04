<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SchoolResource\Pages;
use App\Filament\Resources\SchoolResource\RelationManagers;
use App\Helpers\RoleHelpers;
use App\Helpers\StatusHelpers;
use App\Models\School;
use App\Models\Site;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Closure;

use Illuminate\Support\Facades\Auth;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class SchoolResource extends Resource
{
    protected static ?string $model = School::class;
    protected static ?string $modelLabel= "School";


    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'School Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('name')
                            ->disabled(fn ($livewire) => $livewire instanceof EditRecord)
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search) {
                                return Site::leftJoin('schools', 'sites.site_id', '=', 'schools.site_id')
                                    ->whereNull('schools.site_id')
                                    ->where('sites.site_name', 'like', "%{$search}%")
                                    ->limit(50)
                                    ->pluck('sites.site_name', 'sites.site_id')
                                    ->toArray();
                            })
                            ->getOptionLabelUsing(function ($value) {
                                return $value;
                            })
                            ->label('School Name')
                            ->required(),
                        TinyEditor::make('content_blocks')
                            ->label('School profile')
                            ->fileAttachmentsDisk('local')
                            ->fileAttachmentsVisibility('public')
                            ->fileAttachmentsDirectory('public/uploads/schools')
                            ->required(),
                        Forms\Components\FileUpload::make('cover_image')
                            ->preserveFilenames()
                            ->disk('public')
                            ->directory('uploads/schools')
                            ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png'])
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                return (string)str($file->getClientOriginalName())->prepend('edSpark-schools-');
                            }),
                        Forms\Components\FileUpload::make('logo')
                            ->preserveFilenames()
                            ->disk('public')
                            ->directory('uploads/school')
                            ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png'])
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                return (string)str($file->getClientOriginalName())->prepend('edSpark-schools-');
                            }),

                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('site_id'),
                Tables\Columns\TextColumn::make('name')
                    ->limit(35)
                ,
//                Tables\Columns\TextColumn::make('owner.full_name')->label('Owner')
//                    ->limit(15)
//                ,
                Tables\Columns\ToggleColumn::make('is_featured')
                ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->sortable()
                    ->dateTime('j M y, h:i a'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('j M y, h:i a'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(StatusHelpers::getStatusList())
                    ->label('Guide status')
                    ->default('published')
                    ->attribute('status'),
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('is_featured', 'desc')
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
            'index' => Pages\ListSchools::route('/'),
            'create' => Pages\CreateSchool::route('/create'),
            'edit' => Pages\EditSchool::route('/{record}/edit'),
        ];
    }

    protected function getTableRecordActionUsing(): ?Closure
    {
        return null;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return RoleHelpers::has_minimum_privilege('site_leader');
    }

}

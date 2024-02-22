<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SchoolResource\Pages;
use App\Filament\Resources\SchoolResource\RelationManagers;
use App\Helpers\RoleHelpers;
use App\Models\School;
use Filament\Forms;
use Filament\Forms\Form;
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
                        Forms\Components\TextInput::make('name')
                            ->label('School Name')
                            ->required()
                            ->disabled()
                            ->maxLength(255),
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
                    ->limit(25)
                ,
                Tables\Columns\TextColumn::make('owner.full_name')->label('Owner')
                    ->limit(15)
                ,
                Tables\Columns\ToggleColumn::make('isFeatured'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->sortable()
                    ->dateTime('j M y, h:i a'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('j M y, h:i a'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'published' => 'Published',
                        'pending' => 'Pending Moderation',
                        'archived' => 'Archived',
                        'draft' => 'Draft/Incomplete',
                        'unpublished' => 'Deleted'
                    ])
                    ->label('Guide status')
                    ->default('published')
                    ->attribute('status'),
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
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

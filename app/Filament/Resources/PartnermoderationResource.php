<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartnermoderationResource\Pages;
use App\Filament\Resources\PartnermoderationResource\RelationManagers;
use App\Helpers\RoleHelpers;
use App\Helpers\UserRole;
use App\Models\Partnermoderation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class PartnermoderationResource extends Resource
{
    protected static ?string $model = Partnermoderation::class;
    protected static ?string $modelLabel = 'Partner Moderation';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Moderation';
    protected static ?string $navigationLabel = 'Partner Moderation';
    protected static ?int $navigationSort = 5;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\TextInput::make('introduction')
                        ->label('Introduction')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('motto')
                        ->label('Motto')
                        ->maxLength(255),
                    TinyEditor::make('content')
                        ->label('Partner profile')
                        ->fileAttachmentsDisk('local')
                        ->fileAttachmentsVisibility('public')
                        ->fileAttachmentsDirectory('public/uploads/partners')
                        ->required(),
                    Forms\Components\FileUpload::make('logo')
                        ->preserveFilenames()
                        ->disk('public')
                        ->directory('uploads/partner/logo')
                        ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png'])
                        ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                            return (string)str($file->getClientOriginalName())->prepend('edSpark-partner-logo-');
                        }),
                    Forms\Components\FileUpload::make('cover_image')
                        ->preserveFilenames()
                        ->disk('public')
                        ->directory('uploads/partner/coverimage')
                        ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png'])
                        ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                            return (string)str($file->getClientOriginalName())->prepend('edSpark-partner-');
                        }),
                    Forms\Components\Select::make('status')
                        ->options([
                            'Published' => 'Published',
                            'Unpublished' => 'Unpublished',
                            'Draft' => 'Draft',
                            'Pending' => 'Pending'
                        ])
                        ->label('Status')
                        ->required(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('partner_id')
                    ->limit(20),
                Tables\Columns\TextColumn::make('partner.name')->label('Partner name')
                    ->limit(20),
                Tables\Columns\TextColumn::make('introduction')->label('Introduction')
                    ->limit(20),
                Tables\Columns\TextColumn::make('motto')
                    ->limit(20),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('status', 'Pending');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartnermoderations::route('/'),
            'create' => Pages\CreatePartnermoderation::route('/create'),
            'edit' => Pages\EditPartnermoderation::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::query()->where('status', 'pending')->count();
        if ($count > 0) {
            return $count;
        } else {
            return '';
        }
    }

    public static function shouldRegisterNavigation(): bool
    {
        return RoleHelpers::has_minimum_privilege(UserRole::MODERATOR);
    }
}

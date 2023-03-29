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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\TemporaryUploadedFile;
use Illuminate\Support\Facades\Auth;

class HardwareResource extends Resource
{
    protected static ?string $model = Hardware::class;

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
                            ->acceptedFileTypes(['image/jpeg','image/jpg', 'image/png'])
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                return (string) str($file->getClientOriginalName())->prepend('edSpark-hardware-');
                            }),
                        Forms\Components\FileUpload::make('gallery')
                            ->multiple()
                            ->preserveFilenames()
                            ->disk('public')
                            ->directory('uploads/hardware/gallery')
                            ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png'])
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                return (string) str($file->getClientOriginalName())->prepend('edSpark-hardware-gallery-');
                            })
                            ->enableReordering()
                            ->minFiles(2)
                            ->maxFiles(5),
                        Forms\Components\TextInput::make('price')
                            ->required(),
                        Forms\Components\BelongsToSelect::make('brand')
                            ->relationship('brand', 'product_brand_name'),
                        Forms\Components\BelongsToSelect::make('category')
                            ->relationship('category', 'product_category_name')
                        // Forms\Components\Toggle::make('product_isLoan'),
                    ]),

            ]);
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
        // use Illuminate\Support\Facades\Auth;

        // Moderator check
        if(Auth::user()->role->role_name == 'Moderator') {
            return false;
        }

        return true;
    }
}

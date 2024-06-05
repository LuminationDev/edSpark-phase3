<?php

namespace App\Filament\Resources;

use App\Components\EdsparkImageLibraryPicker;
use App\Filament\Resources\ImageResource\Pages;
use App\Forms\Components\ImagePreview;
use Filament\Forms\Components\TextInput;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Outerweb\FilamentImageLibrary\Filament\Forms\Components\ImageLibraryPicker;
use Outerweb\ImageLibrary\Models\Image;

class ImageResource extends Resource
{
    protected static ?string $model = Image::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Classroom Catalogue';
    protected static ?string $navigationLabel = 'Image Library';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        TextInput::make('title'),
                        TextInput::make('alt'),
                        ImagePreview::make('image preview')


                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Image title')
                    ->sortable()
                    ->limit(30)
                    ->searchable(),
                Tables\Columns\TextColumn::make('alt')
                    ->label('Alt')
                    ->sortable()
                    ->limit(30)
                    ->searchable(),
                Tables\Columns\TextColumn::make('file_extension')
                    ->label('Extension')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('cover_image')
                    ->label('Thumbnail')
                    ->limit(30)
                    ->square()
                    ->getStateUsing(function ($record): string {
                        $image = $record;
                        if ($image) {
                            return env('VITE_SERVER_IMAGE_API') . '/' . $image->uuid . "/original." . $image->file_extension;
                        }
                        return '';
                    })
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListImages::route('/'),
            'create' => Pages\CreateImage::route('/create'),
            'edit' => Pages\EditImage::route('/{record}/edit'),
        ];
    }
}

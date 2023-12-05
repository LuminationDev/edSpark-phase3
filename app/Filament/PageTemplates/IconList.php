<?php

namespace App\Filament\PageTemplates;

use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Repeater;
use Guava\FilamentIconPicker\Forms\IconPicker;
use Guava\FilamentIconPicker\Tables\IconColumn;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;


final class IconList
{
    public static function title()
    {
        return 'Icon List Items';
    }

    public static function schema()
    {
        return [
            Repeater::make('item')->schema([
                IconPicker::make('icon')
                    ->columns(4)
                    ->sets(['fontawesome-solid']),
                TextInput::make('heading'),
                TinyEditor::make('content')
                    ->label('Content')->fileAttachmentsDisk('local')
                    ->fileAttachmentsVisibility('public')
                    ->fileAttachmentsDirectory('public/uploads/image')
                    ->required(),
            ])
                ->label('Item')
                ->collapsible()
        ];
    }
}

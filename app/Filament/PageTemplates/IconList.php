<?php

namespace App\Filament\PageTemplates;

use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Guava\FilamentIconPicker\Forms\IconPicker;
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
            Forms\Components\TextInput::make('title')
                ->label('Resource title')
                ->maxLength(255),
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

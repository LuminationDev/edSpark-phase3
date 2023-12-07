<?php

namespace App\Filament\PageTemplates;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Repeater;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

final class NumberedList
{
    public static function title()
    {
        return 'Numbered Items';
    }

    public static function schema()
    {
        return [
            Repeater::make('item')->schema([
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

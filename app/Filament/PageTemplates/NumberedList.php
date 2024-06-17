<?php

namespace App\Filament\PageTemplates;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Repeater;
use AmidEsfahani\FilamentTinyEditor\TinyEditor;

final class NumberedList
{
    public static function title()
    {
        return 'Numbered Items';
    }

    public static function schema()
    {
        return [
            Forms\Components\TextInput::make('title')
                ->label('Resource title')
                ->maxLength(255),
            Repeater::make('item')->schema([
                TextInput::make('heading'),
                TinyEditor::make('content')
                    ->profile('edspark')
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

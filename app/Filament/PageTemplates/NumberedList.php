<?php

namespace App\Filament\PageTemplates;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Repeater;

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
                RichEditor::make('content')
            ])
                ->label('Item')
                ->collapsible()
        ];
    }
}

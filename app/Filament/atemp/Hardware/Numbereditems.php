<?php

namespace App\Filament\atemp\Hardware;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;


final class Numbereditems
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

<?php

namespace App\Filament\atemp\Hardware;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Guava\FilamentIconPicker\Forms\IconPicker;


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
                IconPicker::make('icon')
                    ->columns(4),
                TextInput::make('heading'),
                RichEditor::make('content')
            ])
            ->label('Item')
            ->collapsible()
        ];
    }
}

<?php

namespace App\Filament\atemp\Event;

use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;

final class dateItems
{
    public static function title()
    {
        return 'Date and Time Template';
    }

    public static function schema()
    {
        return [
            Repeater::make('item')->schema([
                TextInput::make('heading'),
                RichEditor::make('content'),
                Forms\Components\DateTimePicker::make('start_date')
                    ->required(),
            ])
                ->label('Item')
                ->collapsible()
        ];
    }
}

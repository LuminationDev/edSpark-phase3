<?php

namespace App\Filament\atemp\Software;

use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;

final class Extraresource
{
    public static function title()
    {
        return 'Extra Resources';
    }

    public static function schema()
    {
        return [
            Forms\Components\TextInput::make('resource_title')
                ->label('Resource title')
                ->maxLength(255),
            Repeater::make('item')->schema([
                TextInput::make('heading'),
                RichEditor::make('content')
            ])
                ->label('Item')
                ->collapsible()
        ];
    }
}
